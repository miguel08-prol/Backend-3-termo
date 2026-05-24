<template>
  <div class="space-y-8 animate-fadeIn">
    <!-- Header Melhorado -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-700 via-slate-800 to-slate-900 p-6 text-white shadow-lg">
      <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-20 -mt-20"></div>
      <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center">
            <Icon name="heroicons:document-text-20-solid" class="w-6 h-6" />
          </div>
          <div>
            <h1 class="text-2xl font-bold">Histórico de Autorizações</h1>
            <p class="text-slate-300 text-sm">Todas as autorizações registradas para suas turmas</p>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2">
            <p class="text-2xl font-bold">{{ pagination.total }}</p>
            <p class="text-[10px] opacity-80">total registros</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Filtros Melhorados -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Status</label>
          <select v-model="filters.status" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            <option value="">Todos</option>
            <option value="approved_by_professor">✅ Autorizada</option>
            <option value="completed">🏁 Concluída</option>
            <option value="cancelled">❌ Cancelada</option>
          </select>
        </div>
        
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Aluno</label>
          <input v-model="filters.aluno" type="text" placeholder="Nome do aluno..." class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Turma</label>
          <input v-model="filters.turma" type="text" placeholder="Turma..." class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Período</label>
          <select v-model="filters.periodo" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            <option value="">Todos</option>
            <option value="today">Hoje</option>
            <option value="yesterday">Ontem</option>
            <option value="week">Última semana</option>
            <option value="month">Último mês</option>
          </select>
        </div>

        <div class="flex gap-2">
          <button @click="applyFilters" :disabled="loading" class="flex-1 px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-xl text-sm font-medium transition-all flex items-center justify-center gap-2">
            <Icon v-if="loading" name="heroicons:arrow-path-20-solid" class="w-4 h-4 animate-spin" />
            <Icon v-else name="heroicons:magnifying-glass-20-solid" class="w-4 h-4" />
            Filtrar
          </button>
          <button @click="resetFilters" class="px-4 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
            <Icon name="heroicons:arrow-path-20-solid" class="w-4 h-4" />
          </button>
        </div>
      </div>
      
      <div class="flex justify-end mt-4 pt-3 border-t border-slate-100 dark:border-slate-700">
        <button @click="exportToCSV" class="px-5 py-2.5 bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 rounded-xl text-sm font-medium transition-all flex items-center gap-2 hover:bg-emerald-200">
          <Icon name="heroicons:document-arrow-down-20-solid" class="w-4 h-4" />
          Exportar CSV
        </button>
      </div>
    </div>

    <!-- Cards Grid Melhorado -->
    <div v-if="loading && authorizations.length === 0" class="flex justify-center py-20">
      <div class="flex flex-col items-center gap-3">
        <Icon name="heroicons:arrow-path-20-solid" class="w-10 h-10 animate-spin text-blue-500" />
        <p class="text-slate-500 font-medium">Carregando autorizações...</p>
      </div>
    </div>

    <div v-else-if="authorizations.length === 0" class="text-center py-20 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700">
      <div class="w-24 h-24 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center mx-auto mb-4">
        <Icon name="heroicons:document-text-20-solid" class="w-12 h-12 text-slate-400" />
      </div>
      <p class="text-slate-500 font-medium text-lg">Nenhuma autorização encontrada</p>
      <p class="text-sm text-slate-400 mt-1">Tente ajustar os filtros de busca</p>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
      <div v-for="auth in authorizations" :key="auth.id" class="group bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
        <!-- Card Header -->
        <div class="p-4 border-b border-slate-100 dark:border-slate-700 bg-gradient-to-r from-slate-50 to-white dark:from-slate-800/50 dark:to-slate-800 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <div :class="[
              'w-8 h-8 rounded-lg flex items-center justify-center',
              auth.tipo === 'entrada' ? 'bg-emerald-100 dark:bg-emerald-500/20' : 'bg-blue-100 dark:bg-blue-500/20'
            ]">
              <Icon :name="auth.tipo === 'entrada' ? 'heroicons:arrow-left-start-on-rectangle-20-solid' : 'heroicons:arrow-right-start-on-rectangle-20-solid'" 
                    :class="auth.tipo === 'entrada' ? 'text-emerald-600' : 'text-blue-600'" 
                    class="w-4 h-4" />
            </div>
            <span class="text-xs font-mono text-slate-400">#{{ auth.id }}</span>
          </div>
          <div class="flex items-center gap-2">
            <span v-if="auth.com_falta" class="px-2 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400">
              ⚠️ Falta
            </span>
            <span :class="statusBadge(auth.status)" class="px-2 py-1 text-xs font-medium rounded-full">
              {{ statusText(auth.status) }}
            </span>
          </div>
        </div>
        
        <!-- Card Body -->
        <div class="p-5">
          <div class="flex items-start gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-500/20 dark:to-blue-600/20 flex items-center justify-center flex-shrink-0">
              <Icon name="heroicons:user-20-solid" class="w-5 h-5 text-blue-600" />
            </div>
            <div class="flex-1 min-w-0">
              <h3 class="font-bold text-lg text-slate-900 dark:text-white truncate">{{ auth.aluno_nome }}</h3>
              <p class="text-xs text-slate-400 truncate">{{ auth.motivo_saida || 'Sem motivo informado' }}</p>
            </div>
          </div>
          
          <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="p-2 bg-slate-50 dark:bg-slate-700/30 rounded-lg">
              <p class="text-xs text-slate-500">Turma</p>
              <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ auth.turma }}</p>
            </div>
            <div class="p-2 bg-slate-50 dark:bg-slate-700/30 rounded-lg">
              <p class="text-xs text-slate-500">Horário</p>
              <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ formatTime(auth.horario_saida) }}</p>
            </div>
            <div class="p-2 bg-slate-50 dark:bg-slate-700/30 rounded-lg">
              <p class="text-xs text-slate-500">Aula</p>
              <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ auth.aula_numero }}ª</p>
            </div>
            <div class="p-2 bg-slate-50 dark:bg-slate-700/30 rounded-lg">
              <p class="text-xs text-slate-500">Falta</p>
              <p class="text-sm font-medium">
                <span v-if="auth.com_falta" class="text-amber-600">⚠️ Com Falta</span>
                <span v-else class="text-green-600">✅ Sem Falta</span>
              </p>
            </div>
          </div>
          
          <div class="flex items-center gap-1 text-xs text-slate-400">
            <Icon name="heroicons:calendar-20-solid" class="w-3 h-3" />
            {{ formatDate(auth.created_at) }}
          </div>
        </div>
        
        <!-- Card Footer -->
        <div class="p-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/30 flex justify-end">
          <button @click="viewDetails(auth)" class="px-4 py-2 rounded-xl text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-all flex items-center gap-2 text-sm font-medium">
            <Icon name="heroicons:eye-20-solid" class="w-4 h-4" />
            Ver detalhes
          </button>
        </div>
      </div>
    </div>

    <!-- Paginação Melhorada -->
    <div v-if="pagination.total > 0" class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
      <p class="text-sm text-slate-500">
        Mostrando <span class="font-bold">{{ authorizations.length }}</span> de <span class="font-bold">{{ pagination.total }}</span> registros
      </p>
      <div class="flex gap-1">
        <button @click="prevPage" :disabled="pagination.current_page <= 1" class="w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-700 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-slate-100 dark:hover:bg-slate-700 transition-all flex items-center justify-center">
          <Icon name="heroicons:chevron-left-20-solid" class="w-4 h-4" />
        </button>
        <button v-for="page in visiblePages" :key="page" @click="goToPage(page)" :class="[
          'w-9 h-9 rounded-xl transition-all text-sm font-medium',
          pagination.current_page === page 
            ? 'bg-blue-500 text-white shadow-md' 
            : 'border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700'
        ]">
          {{ page }}
        </button>
        <button @click="nextPage" :disabled="pagination.current_page >= pagination.last_page" class="w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-700 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-slate-100 dark:hover:bg-slate-700 transition-all flex items-center justify-center">
          <Icon name="heroicons:chevron-right-20-solid" class="w-4 h-4" />
        </button>
      </div>
    </div>

    <!-- Modal de Detalhes Melhorado -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div v-if="showDetailsModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="showDetailsModal = false">
          <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
          <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-lg w-full max-h-[85vh] overflow-y-auto animate-slideUp">
            <div v-if="selectedAuth">
              <div class="sticky top-0 z-10 bg-gradient-to-r from-slate-700 to-slate-800 p-5 text-white rounded-t-2xl">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                      <Icon name="heroicons:document-text-20-solid" class="w-5 h-5" />
                    </div>
                    <div>
                      <h3 class="text-xl font-bold">{{ selectedAuth.aluno_nome }}</h3>
                      <p class="text-slate-300 text-xs">ID: #{{ selectedAuth.id }}</p>
                    </div>
                  </div>
                  <button @click="showDetailsModal = false" class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 transition-colors flex items-center justify-center">
                    <Icon name="heroicons:x-mark-20-solid" class="w-5 h-5" />
                  </button>
                </div>
              </div>

              <div class="p-5 space-y-4">
                <div class="grid grid-cols-2 gap-3">
                  <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-3">
                    <p class="text-xs text-slate-500">Turma</p>
                    <p class="font-semibold">{{ selectedAuth.turma }}</p>
                  </div>
                  <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-3">
                    <p class="text-xs text-slate-500">Tipo</p>
                    <p class="font-semibold capitalize" :class="selectedAuth.tipo === 'entrada' ? 'text-emerald-600' : 'text-blue-600'">
                      {{ selectedAuth.tipo === 'entrada' ? 'Entrada' : 'Saída' }}
                    </p>
                  </div>
                  <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-3">
                    <p class="text-xs text-slate-500">Horário</p>
                    <p class="font-semibold font-mono">{{ formatTime(selectedAuth.horario_saida) }}</p>
                    <p class="text-xs text-slate-400">{{ selectedAuth.aula_numero }}ª Aula</p>
                  </div>
                  <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-3">
                    <p class="text-xs text-slate-500">Status</p>
                    <span :class="statusBadge(selectedAuth.status)" class="px-2 py-1 text-xs font-medium rounded-full inline-block">
                      {{ statusText(selectedAuth.status) }}
                    </span>
                  </div>
                </div>

                <div v-if="selectedAuth.motivo_saida" class="bg-yellow-50 dark:bg-yellow-500/10 rounded-xl p-4 border border-yellow-200 dark:border-yellow-500/20">
                  <p class="text-xs text-yellow-700 dark:text-yellow-400 flex items-center gap-1">
                    <Icon name="heroicons:chat-bubble-left-20-solid" class="w-3 h-3" />
                    Motivo da Saída
                  </p>
                  <p class="text-sm mt-1">{{ selectedAuth.motivo_saida }}</p>
                </div>

                <div v-if="selectedAuth.observacoes" class="bg-slate-50 dark:bg-slate-800/30 rounded-xl p-4">
                  <p class="text-xs text-slate-500 flex items-center gap-1">
                    <Icon name="heroicons:document-text-20-solid" class="w-3 h-3" />
                    Observações
                  </p>
                  <p class="text-sm mt-1">{{ selectedAuth.observacoes }}</p>
                </div>

                <div v-if="selectedAuth.com_falta" class="bg-amber-50 dark:bg-amber-500/10 rounded-xl p-4 border border-amber-200 dark:border-amber-500/20">
                  <div class="flex items-center gap-2">
                    <Icon name="heroicons:exclamation-triangle-20-solid" class="w-5 h-5 text-amber-600" />
                    <div>
                      <p class="text-sm font-semibold text-amber-700 dark:text-amber-400">Aluno recebeu falta</p>
                      <p class="text-xs text-amber-600 dark:text-amber-500">A autorização gerou registro de falta</p>
                    </div>
                  </div>
                </div>

                <div class="flex justify-between text-xs text-slate-400 border-t border-slate-100 dark:border-slate-700 pt-4 mt-2">
                  <span>Criado por: {{ selectedAuth.admin?.name || 'Coordenação' }}</span>
                  <span>{{ formatDate(selectedAuth.created_at) }}</span>
                </div>
              </div>

              <div class="border-t border-slate-100 dark:border-slate-700 p-4 bg-slate-50 dark:bg-slate-800/30 rounded-b-2xl flex justify-end">
                <button @click="showDetailsModal = false" class="px-5 py-2.5 rounded-xl bg-slate-600 hover:bg-slate-700 text-white transition-all text-sm font-medium">
                  Fechar
                </button>
              </div>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- Toast Notifications -->
    <div v-if="toastMessage" class="fixed bottom-4 right-4 z-50 animate-slideUp">
      <div :class="[
        'px-5 py-3 rounded-xl shadow-lg flex items-center gap-2',
        toastVariant === 'success' ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white'
      ]">
        <Icon :name="toastVariant === 'success' ? 'heroicons:check-circle-20-solid' : 'heroicons:x-circle-20-solid'" class="w-5 h-5" />
        {{ toastMessage }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useApi } from '~/composables/useApi'

definePageMeta({
  middleware: 'auth',
  layout: 'professor'
})

const { get } = useApi()

const authorizations = ref([])
const loading = ref(false)
const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 20,
  total: 0
})

const filters = ref({
  status: '',
  aluno: '',
  turma: '',
  periodo: ''
})

const showDetailsModal = ref(false)
const selectedAuth = ref(null)
const toastMessage = ref('')
const toastVariant = ref('success')

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

const showToast = (message, variant = 'success') => {
  toastMessage.value = message
  toastVariant.value = variant
  setTimeout(() => {
    toastMessage.value = ''
  }, 3000)
}

const loadData = async (page = 1) => {
  loading.value = true
  try {
    const params = { ...filters.value, page }
    
    if (filters.value.periodo) {
      const today = new Date()
      switch (filters.value.periodo) {
        case 'today':
          params.data_inicio = today.toISOString().split('T')[0]
          break
        case 'yesterday':
          const yesterday = new Date(today)
          yesterday.setDate(today.getDate() - 1)
          params.data_inicio = yesterday.toISOString().split('T')[0]
          break
        case 'week':
          const weekAgo = new Date(today)
          weekAgo.setDate(today.getDate() - 7)
          params.data_inicio = weekAgo.toISOString().split('T')[0]
          break
        case 'month':
          const monthAgo = new Date(today)
          monthAgo.setMonth(today.getMonth() - 1)
          params.data_inicio = monthAgo.toISOString().split('T')[0]
          break
      }
    }
    
    Object.keys(params).forEach(key => {
      if (!params[key] || params[key] === '') delete params[key]
    })
    
    const response = await get('/professor/authorizations/history', params)
    authorizations.value = response.data || response
    pagination.value = {
      current_page: response.current_page || 1,
      last_page: response.last_page || 1,
      per_page: response.per_page || 20,
      total: response.total || 0
    }
  } catch (error) {
    console.error('Erro ao carregar histórico:', error)
    showToast('Erro ao carregar histórico', 'error')
  } finally {
    loading.value = false
  }
}

const exportToCSV = async () => {
  try {
    const params = { ...filters.value, per_page: 5000 }
    
    if (filters.value.periodo) {
      const today = new Date()
      switch (filters.value.periodo) {
        case 'today':
          params.data_inicio = today.toISOString().split('T')[0]
          break
        case 'yesterday':
          const yesterday = new Date(today)
          yesterday.setDate(today.getDate() - 1)
          params.data_inicio = yesterday.toISOString().split('T')[0]
          break
        case 'week':
          const weekAgo = new Date(today)
          weekAgo.setDate(today.getDate() - 7)
          params.data_inicio = weekAgo.toISOString().split('T')[0]
          break
        case 'month':
          const monthAgo = new Date(today)
          monthAgo.setMonth(today.getMonth() - 1)
          params.data_inicio = monthAgo.toISOString().split('T')[0]
          break
      }
    }
    
    Object.keys(params).forEach(key => {
      if (!params[key] || params[key] === '') delete params[key]
    })
    
    const response = await get('/professor/authorizations/history', params)
    const data = response.data || response
    
    if (!data || data.length === 0) {
      showToast('Não há dados para exportar', 'error')
      return
    }
    
    const headers = ['ID', 'Aluno', 'Turma', 'Tipo', 'Horário', 'Aula', 'Motivo', 'Status', 'Com Falta', 'Data Criação']
    const rows = data.map(auth => [
      auth.id,
      auth.aluno_nome,
      auth.turma,
      auth.tipo === 'entrada' ? 'Entrada' : 'Saída',
      auth.horario_saida,
      `${auth.aula_numero}ª`,
      auth.motivo_saida || '-',
      statusText(auth.status),
      auth.com_falta ? 'Sim' : 'Não',
      new Date(auth.created_at).toLocaleString('pt-BR')
    ])
    
    const csvContent = [headers, ...rows].map(row => row.join(';')).join('\n')
    const blob = new Blob(['\uFEFF' + csvContent], { type: 'text/csv;charset=utf-8;' })
    const url = URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `historico_professor_${new Date().toISOString().split('T')[0]}.csv`
    a.click()
    URL.revokeObjectURL(url)
    showToast('✅ Histórico exportado com sucesso!', 'success')
  } catch (error) {
    console.error('Erro ao exportar:', error)
    showToast('Erro ao exportar dados', 'error')
  }
}

const applyFilters = () => loadData(1)
const resetFilters = () => {
  filters.value = { status: '', aluno: '', turma: '', periodo: '' }
  loadData(1)
}

const goToPage = (page) => page !== '...' && loadData(page)
const prevPage = () => pagination.value.current_page > 1 && loadData(pagination.value.current_page - 1)
const nextPage = () => pagination.value.current_page < pagination.value.last_page && loadData(pagination.value.current_page + 1)

const viewDetails = (auth) => {
  selectedAuth.value = auth
  showDetailsModal.value = true
}

const formatTime = (time) => time?.substring(0, 5) || '—'
const formatDate = (date) => date ? new Date(date).toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit' }) : '—'

const statusBadge = (status) => {
  const badges = {
    approved_by_professor: 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400',
    completed: 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
    cancelled: 'bg-gray-100 text-gray-700 dark:bg-gray-500/20 dark:text-gray-400'
  }
  return badges[status] || 'bg-green-100 text-green-700'
}

const statusText = (status) => {
  const texts = {
    approved_by_professor: 'Autorizada',
    completed: 'Concluída',
    cancelled: 'Cancelada'
  }
  return texts[status] || status
}

onMounted(() => loadData())
</script>

<style scoped>
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fadeIn { animation: fadeInUp 0.5s ease-out forwards; }
.animate-slideUp { animation: slideUp 0.3s ease-out forwards; }

.modal-fade-enter-active,
.modal-fade-leave-active { transition: opacity 0.2s ease; }
.modal-fade-enter-from,
.modal-fade-leave-to { opacity: 0; }
</style>