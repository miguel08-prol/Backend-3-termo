<template>
  <div class="space-y-6 animate-fadeIn">
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-600 via-emerald-500 to-teal-500 p-8 text-white shadow-lg shadow-emerald-500/20">
      <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
      <div class="absolute bottom-0 left-0 w-40 h-40 bg-black/10 rounded-full blur-2xl -ml-10 -mb-10 pointer-events-none"></div>
      
      <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-inner">
            <Icon name="heroicons:document-text-20-solid" class="w-7 h-7 text-white" />
          </div>
          <div>
            <h1 class="text-3xl font-bold tracking-tight">Histórico de Saídas</h1>
            <p class="text-emerald-50 text-sm mt-1 font-medium opacity-90">Monitoramento e registro de acessos da portaria</p>
          </div>
        </div>
        
        <div class="flex items-center">
          <div class="bg-white/10 backdrop-blur-md rounded-2xl px-6 py-3 border border-white/10 text-right">
            <p class="text-[11px] uppercase tracking-wider font-bold text-emerald-100 mb-0.5">Total de Registros</p>
            <p class="text-3xl font-extrabold leading-none">{{ pagination.total || 0 }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800 p-6 shadow-sm">
      <div class="flex flex-col lg:flex-row gap-4 items-end">
        <div class="w-full lg:w-1/3">
          <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2">Buscar Registro</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <Icon name="heroicons:magnifying-glass-20-solid" class="w-5 h-5 text-slate-400" />
            </div>
            <input v-model="filters.search" type="text" placeholder="Nome do aluno, turma, motivo..." class="w-full pl-10 pr-3 py-2.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none text-slate-700 dark:text-slate-200">
          </div>
        </div>

        <div class="w-full lg:w-1/3 flex gap-3">
          <div class="w-1/2">
            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2">Data Inicial</label>
            <input v-model="filters.start_date" type="date" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none text-slate-700 dark:text-slate-200">
          </div>
          <div class="w-1/2">
            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2">Data Final</label>
            <input v-model="filters.end_date" type="date" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none text-slate-700 dark:text-slate-200">
          </div>
        </div>

        <div class="w-full lg:w-1/3 flex gap-2">
          <button @click="applyFilters" class="flex-1 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition-all flex items-center justify-center gap-2 shadow-sm shadow-emerald-500/20">
            Filtrar
          </button>
          <button @click="resetFilters" title="Limpar Filtros" class="px-4 py-2.5 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 active:bg-slate-100 transition-all flex items-center justify-center">
            <Icon name="heroicons:x-mark-20-solid" class="w-5 h-5" />
          </button>
          <div class="w-px h-10 bg-slate-200 dark:bg-slate-700 mx-1 hidden sm:block"></div>
          <button @click="exportToCSV" title="Exportar para CSV" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-medium transition-all flex items-center justify-center gap-2">
            <Icon name="heroicons:arrow-down-tray-20-solid" class="w-5 h-5" />
            <span class="hidden xl:inline">Exportar</span>
          </button>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800 overflow-hidden shadow-sm flex flex-col">
      <div v-if="loadingHistory" class="flex flex-col items-center justify-center py-24">
        <Icon name="heroicons:arrow-path-20-solid" class="w-8 h-8 animate-spin text-emerald-500 mb-4" />
        <p class="text-slate-500 font-medium text-sm">Buscando registros...</p>
      </div>

      <div v-else-if="history.length === 0" class="flex flex-col items-center justify-center py-24 px-4 text-center">
        <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center mb-4 border border-slate-100 dark:border-slate-700">
          <Icon name="heroicons:inbox-20-solid" class="w-10 h-10 text-slate-300 dark:text-slate-500" />
        </div>
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-1">Nenhum registro encontrado</h3>
        <p class="text-sm text-slate-500 max-w-sm">Não há saídas registradas para os filtros aplicados. Tente alterar as datas ou o termo de busca.</p>
        <button @click="resetFilters" class="mt-6 text-sm font-medium text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-4 py-2 rounded-lg transition-colors">
          Limpar todos os filtros
        </button>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
          <thead class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-slate-200/60 dark:border-slate-700/60 backdrop-blur-sm">
            <tr>
              <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">Aluno / Motivo</th>
              <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">Turma</th>
              <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">Horário</th>
              <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">Aula / Prof</th>
              <th class="px-6 py-4 text-center text-[11px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">Falta</th>
              <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">Registro</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            <tr v-for="entry in history" :key="entry.id" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-colors group">
              
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-900/40 dark:to-emerald-800/40 flex items-center justify-center text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-700/50 flex-shrink-0">
                    <span class="text-sm font-bold">{{ entry.authorization?.aluno_nome?.charAt(0).toUpperCase() || 'A' }}</span>
                  </div>
                  <div>
                    <p class="font-semibold text-slate-900 dark:text-slate-100 text-sm group-hover:text-emerald-600 transition-colors">{{ entry.authorization?.aluno_nome }}</p>
                    <p class="text-[12px] text-slate-500 dark:text-slate-400 truncate max-w-[200px]" :title="entry.authorization?.motivo_saida">
                      {{ entry.authorization?.motivo_saida || 'Sem motivo registrado' }}
                    </p>
                  </div>
                </div>
              </td>

              <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 rounded-md border border-slate-200 dark:border-slate-700">
                  {{ entry.authorization?.turma || '-' }}
                </span>
              </td>

              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <Icon name="heroicons:clock-20-solid" class="w-4 h-4 text-emerald-500" />
                  <p class="font-mono text-sm font-bold text-slate-900 dark:text-slate-100">{{ formatTime(entry.horario_saida) }}</p>
                </div>
              </td>

              <td class="px-6 py-4">
                <p class="text-sm text-slate-900 dark:text-slate-200 font-medium">{{ entry.authorization?.aula_numero ? `${entry.authorization.aula_numero}ª Aula` : '-' }}</p>
                <p class="text-[12px] text-slate-500 flex items-center gap-1 mt-0.5">
                  <Icon name="heroicons:academic-cap-20-solid" class="w-3 h-3" />
                  {{ entry.authorization?.professor?.name?.split(' ')[0] || 'N/A' }}
                </p>
              </td>

              <td class="px-6 py-4 text-center">
                <span v-if="entry.authorization?.com_falta" class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider rounded-full bg-red-50 text-red-600 border border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20">
                  <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div> Sim
                </span>
                <span v-else class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20">
                  <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div> Não
                </span>
              </td>

              <td class="px-6 py-4">
                <div class="flex flex-col">
                  <div class="flex items-center gap-1.5 text-sm text-slate-900 dark:text-slate-200 font-medium">
                    <Icon name="heroicons:shield-check-20-solid" class="w-4 h-4 text-slate-400" />
                    {{ entry.portaria?.name?.split(' ')[0] || '-' }}
                  </div>
                  <div class="flex items-center gap-1 text-[11px] text-slate-400 mt-0.5">
                    <span :title="formatDateTime(entry.created_at)">{{ formatRelativeTime(entry.created_at) }}</span>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="pagination.total > 0 && !loadingHistory" class="px-6 py-4 border-t border-slate-200/60 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 flex flex-col sm:flex-row justify-between items-center gap-4">
        <p class="text-sm text-slate-500 dark:text-slate-400">
          Exibindo <span class="font-semibold text-slate-900 dark:text-white">{{ history.length }}</span> de <span class="font-semibold text-slate-900 dark:text-white">{{ pagination.total }}</span> resultados
        </p>
        
        <div class="flex items-center gap-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl p-1 shadow-sm">
          <button @click="prevPage" :disabled="pagination.current_page <= 1" class="w-8 h-8 rounded-lg text-slate-500 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center">
            <Icon name="heroicons:chevron-left-20-solid" class="w-5 h-5" />
          </button>
          
          <div class="flex gap-1 px-1">
            <button v-for="page in visiblePages" :key="page" @click="goToPage(page)" 
              :class="[
                'min-w-[32px] h-8 px-2 rounded-lg transition-all text-sm font-semibold flex items-center justify-center',
                pagination.current_page === page 
                  ? 'bg-emerald-500 text-white shadow-sm' 
                  : page === '...' 
                    ? 'text-slate-400 cursor-default hover:bg-transparent' 
                    : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'
              ]"
              :disabled="page === '...'"
            >
              {{ page }}
            </button>
          </div>

          <button @click="nextPage" :disabled="pagination.current_page >= pagination.last_page" class="w-8 h-8 rounded-lg text-slate-500 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center">
            <Icon name="heroicons:chevron-right-20-solid" class="w-5 h-5" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useGateway } from '~/composables/useGateway'
import { useToast } from '~/composables/useToast'

definePageMeta({
  middleware: 'auth',
  layout: 'gateway'
})

const { history, loadingHistory, pagination, fetchHistory } = useGateway()
const { show: toast } = useToast()

const filters = ref({
  start_date: '',
  end_date: '',
  search: ''
})

const visiblePages = computed(() => {
  const current = pagination.value.current_page
  const last = pagination.value.last_page
  const delta = 1
  const range = []
  for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
    range.push(i)
  }
  if (current - delta > 2) range.unshift('...')
  if (current + delta < last - 1) range.push('...')
  range.unshift(1)
  if (last !== 1 && last !== undefined) range.push(last)
  return range
})

const loadData = async (page = 1) => {
  const params = { page }
  if (filters.value.start_date) params.start_date = filters.value.start_date
  if (filters.value.end_date) params.end_date = filters.value.end_date
  if (filters.value.search) params.search = filters.value.search
  
  await fetchHistory(params)
}

const applyFilters = () => loadData(1)
const resetFilters = () => {
  filters.value = { start_date: '', end_date: '', search: '' }
  loadData(1)
}

const goToPage = (page) => page !== '...' && loadData(page)
const prevPage = () => pagination.value.current_page > 1 && loadData(pagination.value.current_page - 1)
const nextPage = () => pagination.value.current_page < pagination.value.last_page && loadData(pagination.value.current_page + 1)

const exportToCSV = async () => {
  try {
    const params = {}
    if (filters.value.start_date) params.start_date = filters.value.start_date
    if (filters.value.end_date) params.end_date = filters.value.end_date
    if (filters.value.search) params.search = filters.value.search
    params.per_page = 5000
    
    const response = await fetchHistory(params)
    const data = response.data || response
    
    if (!data || data.length === 0) {
      toast('Não há dados para exportar', 'error')
      return
    }
    
    const headers = ['ID', 'Aluno', 'Turma', 'Horário Saída', 'Aula', 'Motivo', 'Professor', 'Com Falta', 'Registrado Por', 'Data/Hora Registro']
    const rows = data.map(entry => [
      entry.id,
      entry.authorization?.aluno_nome || '-',
      entry.authorization?.turma || '-',
      formatTime(entry.horario_saida),
      `${entry.authorization?.aula_numero || '-'}ª`,
      entry.authorization?.motivo_saida || '-',
      entry.authorization?.professor?.name || '-',
      entry.authorization?.com_falta ? 'Sim' : 'Não',
      entry.portaria?.name || '-',
      new Date(entry.created_at).toLocaleString('pt-BR')
    ])
    
    const csvContent = [headers, ...rows].map(row => row.join(';')).join('\n')
    const blob = new Blob(['\uFEFF' + csvContent], { type: 'text/csv;charset=utf-8;' })
    const url = URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `historico_saidas_${new Date().toISOString().split('T')[0]}.csv`
    a.click()
    URL.revokeObjectURL(url)
    toast('✅ Histórico exportado com sucesso!', 'success')
  } catch (error) {
    console.error('Erro ao exportar:', error)
    toast('Erro ao exportar dados', 'error')
  }
}

const formatTime = (time) => {
  if (!time) return '—'
  const date = new Date(time)
  if (!isNaN(date.getTime())) return date.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' })
  return time.substring(0, 5)
}

const formatDateTime = (date) => {
  if (!date) return '—'
  return new Date(date).toLocaleString('pt-BR')
}

const formatRelativeTime = (date) => {
  if (!date) return ''
  const diff = Math.floor((new Date() - new Date(date)) / 1000 / 60)
  if (diff < 1) return 'agora mesmo'
  if (diff < 60) return `${diff} min atrás`
  if (diff < 1440) return `${Math.floor(diff / 60)}h atrás`
  return `${Math.floor(diff / 1440)} dias atrás`
}

onMounted(() => loadData())
</script>

<style scoped>
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn { 
  animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; 
}

/* Esconde a setinha padrão de input date em alguns navegadores se preferir visual limpo */
input[type="date"]::-webkit-calendar-picker-indicator {
  cursor: pointer;
  opacity: 0.6;
  transition: 0.2s;
}
input[type="date"]::-webkit-calendar-picker-indicator:hover {
  opacity: 1;
}
</style>