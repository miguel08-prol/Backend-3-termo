<template>
  <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
    <div class="p-4 border-b border-slate-100 dark:border-slate-700 bg-gradient-to-r from-amber-50 to-white dark:from-amber-500/10 dark:to-transparent">
      <div class="flex items-center gap-2">
        <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center">
          <Icon name="heroicons:exclamation-triangle-20-solid" class="w-4 h-4 text-amber-600" />
        </div>
        <h3 class="font-bold text-slate-800 dark:text-white">Registro de Faltas</h3>
      </div>
    </div>
    
    <div class="p-4 space-y-3">
      <div v-if="loading" class="flex justify-center py-4">
        <Icon name="heroicons:arrow-path-20-solid" class="w-5 h-5 animate-spin text-amber-500" />
      </div>
      
      <div v-else-if="resumo">
        <div class="grid grid-cols-2 gap-3 mb-4">
          <div class="text-center p-3 bg-red-50 dark:bg-red-500/10 rounded-xl">
            <p class="text-2xl font-bold text-red-600">{{ resumo.total_faltas || 0 }}</p>
            <p class="text-xs text-slate-500">Total de Faltas</p>
          </div>
          <div class="text-center p-3 bg-amber-50 dark:bg-amber-500/10 rounded-xl">
            <p class="text-2xl font-bold text-amber-600">{{ resumo.faltas_mes || 0 }}</p>
            <p class="text-xs text-slate-500">Faltas no Mês</p>
          </div>
        </div>
        
        <div v-if="resumo.ultima_falta" class="text-sm text-slate-500 flex items-center gap-2">
          <Icon name="heroicons:calendar-20-solid" class="w-4 h-4" />
          Última falta: {{ formatDate(resumo.ultima_falta) }}
        </div>
        
        <!-- Faltas por aula -->
        <div v-if="resumo.faltas_por_aula && resumo.faltas_por_aula.length > 0" class="mt-3">
          <p class="text-xs font-semibold text-slate-400 uppercase mb-2">Distribuição por aula</p>
          <div class="space-y-1">
            <div v-for="aula in resumo.faltas_por_aula" :key="aula.aula_numero" class="flex items-center gap-2">
              <span class="text-sm w-16">{{ aula.aula_numero }}ª Aula</span>
              <div class="flex-1 h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                <div class="h-full bg-red-500 rounded-full" :style="{ width: `${(aula.total / resumo.total_faltas) * 100}%` }"></div>
              </div>
              <span class="text-sm font-medium">{{ aula.total }}</span>
            </div>
          </div>
        </div>
      </div>
      
      <div v-else class="text-center py-4">
        <Icon name="heroicons:check-circle-20-solid" class="w-8 h-8 text-green-500 mx-auto mb-2" />
        <p class="text-sm text-slate-500">Sem faltas registradas</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useFaltas } from '~/composables/useFaltas'

const props = defineProps({
  alunoId: {
    type: Number,
    required: true
  }
})

const { fetchResumoFaltas, resumoFaltas, loading } = useFaltas()
const resumo = ref(null)

const formatDate = (date) => {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('pt-BR')
}

const loadResumo = async () => {
  if (props.alunoId) {
    const data = await fetchResumoFaltas(props.alunoId)
    resumo.value = data
  }
}

watch(() => props.alunoId, loadResumo, { immediate: true })
</script>