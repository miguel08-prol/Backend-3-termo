<template>
  <div>
    <div class="mb-8">
      <div class="flex items-center gap-3 mb-2">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
          <Icon name="heroicons:clock-20-solid" class="w-6 h-6 text-white"/>
        </div>
        <div>
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Registro de Saídas</h1>
          <p class="text-slate-500 dark:text-slate-400">Histórico completo de todas as saídas registradas</p>
        </div>
      </div>
    </div>

    <!-- Filtros -->
    <AppCard class="mb-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Data Inicial</label>
          <input v-model="filters.start_date" type="date" class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl">
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Data Final</label>
          <input v-model="filters.end_date" type="date" class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl">
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Buscar</label>
          <input v-model="filters.search" type="text" placeholder="Aluno ou turma..." class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl">
        </div>
        <div class="flex items-end">
          <button @click="applyFilters" class="w-full px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl transition-colors">
            Filtrar
          </button>
        </div>
      </div>
    </AppCard>

    <!-- Tabela de Histórico -->
    <AppCard no-padding>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-slate-50 dark:bg-slate-800/50 border-b-2 border-slate-200 dark:border-slate-700">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Aluno</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Turma</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Horário Saída</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Professor</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Registrado Por</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Falta</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Data/Hora</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            <tr v-for="entry in history" :key="entry.id" class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
              <td class="px-6 py-4">
                <div class="font-medium text-slate-900 dark:text-white">{{ entry.authorization?.aluno_nome }}</div>
                <div class="text-xs text-slate-400">{{ entry.authorization?.motivo_saida || '-' }}</div>
              </td>
              <td class="px-6 py-4">
                <span class="px-2 py-1 text-xs font-mono bg-slate-100 dark:bg-slate-700 rounded-lg">{{ entry.authorization?.turma }}</span>
              </td>
              <td class="px-6 py-4">
                <div class="font-mono text-sm font-semibold text-slate-700 dark:text-slate-300">{{ formatTime(entry.horario_saida) }}</div>
                <div class="text-xs text-slate-400">{{ entry.authorization?.aula_numero }}ª Aula</div>
              </td>
              <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ entry.authorization?.professor?.name || '—' }}</td>
              <td class="px-6 py-4">
                <div class="text-slate-600 dark:text-slate-400">{{ entry.portaria?.name }}</div>
                <div class="text-xs text-slate-400">{{ entry.portaria?.role || 'portaria' }}</div>
              </td>
              <td class="px-6 py-4">
                <span v-if="entry.authorization?.com_falta" class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400">
                  Com Falta
                </span>
                <span v-else class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400">
                  Sem Falta
                </span>
              </td>
              <td class="px-6 py-4">
                <div class="text-slate-600 dark:text-slate-400">{{ formatDateTime(entry.created_at) }}</div>
                <div class="text-xs text-slate-400">{{ formatRelativeTime(entry.created_at) }}</div>
              </td>
            </tr>
            
            <tr v-if="!loading && history.length === 0">
              <td colspan="7" class="px-6 py-12 text-center">
                <Icon name="heroicons:document-text-20-solid" class="w-16 h-16 mx-auto mb-3 text-slate-300 dark:text-slate-600"/>
                <p class="text-slate-500 dark:text-slate-400">Nenhum registro encontrado</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Paginação -->
      <div v-if="pagination" class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-4">
        <p class="text-sm text-slate-500">
          Mostrando <span class="font-semibold">{{ history.length }}</span> de <span class="font-semibold">{{ pagination.total || 0 }}</span> registros
        </p>
        <div class="flex gap-2">
          <button @click="prevPage" :disabled="pagination.current_page <= 1" class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 disabled:opacity-50 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            Anterior
          </button>
          <button @click="nextPage" :disabled="pagination.current_page >= pagination.last_page" class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 disabled:opacity-50 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            Próxima
          </button>
        </div>
      </div>
    </AppCard>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useGateway } from '~/composables/useGateway'
import AppCard from '~/components/common/AppCard.vue'

definePageMeta({
  middleware: 'auth',
  layout: 'gateway'
})

const { history, loading, pagination, fetchHistory } = useGateway()

const filters = ref({
  start_date: '',
  end_date: '',
  search: ''
})

const loadData = async (page = 1) => {
  const params = { page }
  if (filters.value.start_date) params.start_date = filters.value.start_date
  if (filters.value.end_date) params.end_date = filters.value.end_date
  if (filters.value.search) params.search = filters.value.search
  
  await fetchHistory(params)
}

const applyFilters = () => {
  loadData(1) // Sempre reseta para a página 1 ao filtrar
}

const prevPage = () => {
  if (pagination.value && pagination.value.current_page > 1) {
    loadData(pagination.value.current_page - 1)
  }
}

const nextPage = () => {
  if (pagination.value && pagination.value.current_page < pagination.value.last_page) {
    loadData(pagination.value.current_page + 1)
  }
}

const formatTime = (time) => {
  if (!time) return '—'
  const date = new Date(time)
  if (isNaN(date.getTime())) return time.substring(0, 5)
  return date.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' })
}

const formatDateTime = (date) => {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('pt-BR', { 
    day: '2-digit', 
    month: '2-digit', 
    hour: '2-digit', 
    minute: '2-digit' 
  })
}

const formatRelativeTime = (date) => {
  if (!date) return ''
  const diff = Math.floor((new Date() - new Date(date)) / 1000 / 60)
  if (diff < 1) return 'agora mesmo'
  if (diff < 60) return `${diff} min atrás`
  if (diff < 1440) return `${Math.floor(diff / 60)} horas atrás`
  return `${Math.floor(diff / 1440)} dias atrás`
}

onMounted(() => {
  loadData()
})
</script>