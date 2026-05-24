<template>
  <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Data</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Aula</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Justificativa</th>
            <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Ações</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
          <tr v-for="falta in faltas" :key="falta.id" class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
            <td class="px-4 py-3 text-sm">{{ formatDate(falta.data) }}</td>
            <td class="px-4 py-3 text-sm">{{ falta.aula_numero }}ª Aula</td>
            <td class="px-4 py-3">
              <span :class="statusBadge(falta.status)" class="px-2 py-1 text-xs rounded-full">
                {{ statusText(falta.status) }}
              </span>
            </td>
            <td class="px-4 py-3 text-sm text-slate-500 max-w-[200px] truncate">
              {{ falta.justificativa || '—' }}
            </td>
            <td class="px-4 py-3 text-right">
              <button 
                v-if="falta.status === 'falta'"
                @click="openJustifyModal(falta)"
                class="p-1.5 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-colors"
                title="Justificar falta"
              >
                <Icon name="heroicons:pencil-square-20-solid" class="w-4 h-4" />
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <div v-if="faltas.length === 0 && !loading" class="text-center py-8">
      <Icon name="heroicons:check-badge-20-solid" class="w-8 h-8 text-green-500 mx-auto mb-2" />
      <p class="text-sm text-slate-500">Nenhuma falta registrada</p>
    </div>
    
    <!-- Modal de Justificativa -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div v-if="showJustifyModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="closeJustifyModal">
          <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
          <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full">
            <div class="bg-gradient-to-r from-amber-500 to-amber-600 p-5 text-white rounded-t-2xl">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                  <Icon name="heroicons:document-text-20-solid" class="w-5 h-5" />
                </div>
                <div>
                  <h2 class="text-xl font-bold">Justificar Falta</h2>
                  <p class="text-amber-100 text-xs">Informe o motivo da justificativa</p>
                </div>
              </div>
            </div>
            
            <div class="p-5">
              <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                  Justificativa <span class="text-red-500">*</span>
                </label>
                <textarea 
                  v-model="justificativa"
                  rows="4"
                  class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-amber-500"
                  placeholder="Digite o motivo da falta..."
                ></textarea>
              </div>
              
              <div class="flex justify-end gap-2">
                <button @click="closeJustifyModal" class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-50 transition-colors">
                  Cancelar
                </button>
                <button @click="submitJustify" :disabled="submitting" class="px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white transition-all flex items-center gap-2">
                  <Icon v-if="submitting" name="heroicons:arrow-path-20-solid" class="w-4 h-4 animate-spin" />
                  <Icon v-else name="heroicons:check-20-solid" class="w-4 h-4" />
                  {{ submitting ? 'Salvando...' : 'Justificar' }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useFaltas } from '~/composables/useFaltas'
import { useToast } from '~/composables/useToast'

const props = defineProps({
  alunoId: {
    type: Number,
    required: true
  },
  periodo: {
    type: Object,
    default: () => ({ mes: null, ano: null })
  }
})

const { faltas, loading, fetchFaltasByAluno, justificarFalta } = useFaltas()
const { show: toast } = useToast()

const showJustifyModal = ref(false)
const selectedFalta = ref(null)
const justificativa = ref('')
const submitting = ref(false)

const formatDate = (date) => {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('pt-BR')
}

const statusBadge = (status) => {
  const badges = {
    falta: 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400',
    presente: 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400',
    justificado: 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400'
  }
  return badges[status] || 'bg-gray-100 text-gray-700'
}

const statusText = (status) => {
  const texts = {
    falta: 'Falta',
    presente: 'Presente',
    justificado: 'Justificada'
  }
  return texts[status] || status
}

const openJustifyModal = (falta) => {
  selectedFalta.value = falta
  justificativa.value = ''
  showJustifyModal.value = true
}

const closeJustifyModal = () => {
  showJustifyModal.value = false
  selectedFalta.value = null
  justificativa.value = ''
}

const submitJustify = async () => {
  if (!justificativa.value.trim()) {
    toast('Digite uma justificativa', 'warning')
    return
  }
  
  submitting.value = true
  try {
    await justificarFalta(selectedFalta.value.id, justificativa.value)
    toast('Falta justificada com sucesso!', 'success')
    closeJustifyModal()
    await loadFaltas()
  } catch (error) {
    toast(error.message || 'Erro ao justificar falta', 'error')
  } finally {
    submitting.value = false
  }
}

const loadFaltas = async () => {
  if (props.alunoId) {
    const params = {}
    if (props.periodo.mes) params.mes = props.periodo.mes
    if (props.periodo.ano) params.ano = props.periodo.ano
    await fetchFaltasByAluno(props.alunoId, params)
  }
}

watch(() => [props.alunoId, props.periodo], loadFaltas, { immediate: true })
</script>