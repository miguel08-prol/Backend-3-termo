<template>
  <div class="space-y-8 animate-fadeIn">
    <!-- Hero Section Melhorada -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-700 via-slate-800 to-slate-900 p-8 text-white shadow-2xl">
      <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
      <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-24 -mb-24"></div>
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl"></div>
      
      <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center shadow-lg">
            <Icon name="heroicons:academic-cap-20-solid" class="w-8 h-8" />
          </div>
          <div>
            <h1 class="text-3xl font-bold tracking-tight">Dashboard do Professor</h1>
            <p class="text-slate-300 mt-1 flex items-center gap-2">
              <span class="inline-block w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
              Bem-vindo(a), <span class="font-semibold text-blue-300">{{ user?.name?.split(' ')[0] || 'Professor(a)' }}</span>
            </p>
          </div>
        </div>
        
        <div class="flex items-center gap-3">
          <div class="bg-white/10 backdrop-blur-sm rounded-2xl px-5 py-3 text-center">
            <p class="text-2xl font-bold font-mono">{{ currentTime }}</p>
            <p class="text-[11px] opacity-80">{{ currentDate }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Cards de Estatísticas Melhorados -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
      <div class="group bg-white dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Autorizações</p>
            <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">{{ stats.total || 0 }}</p>
            <p class="text-xs text-slate-400 mt-1">últimos 30 dias</p>
          </div>
          <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center transition-all duration-300 group-hover:scale-110">
            <Icon name="heroicons:document-text-20-solid" class="w-6 h-6 text-blue-600" />
          </div>
        </div>
        <div class="mt-3 h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
          <div class="h-full bg-gradient-to-r from-blue-400 to-blue-500 rounded-full transition-all duration-500" :style="{ width: `${Math.min((stats.total / 100) * 100, 100)}%` }"></div>
        </div>
      </div>

      <div class="group bg-white dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Autorizações Hoje</p>
            <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">{{ stats.hoje || 0 }}</p>
            <p class="text-xs text-slate-400 mt-1">registros do dia</p>
          </div>
          <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center transition-all duration-300 group-hover:scale-110">
            <Icon name="heroicons:calendar-20-solid" class="w-6 h-6 text-emerald-600" />
          </div>
        </div>
        <div class="mt-3 h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
          <div class="h-full bg-gradient-to-r from-emerald-400 to-emerald-500 rounded-full transition-all duration-500" :style="{ width: `${Math.min((stats.hoje / 50) * 100, 100)}%` }"></div>
        </div>
      </div>

      <div class="group bg-white dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Este Mês</p>
            <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">{{ stats.mes || 0 }}</p>
            <p class="text-xs text-slate-400 mt-1">total no mês</p>
          </div>
          <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-500/20 flex items-center justify-center transition-all duration-300 group-hover:scale-110">
            <Icon name="heroicons:chart-bar-20-solid" class="w-6 h-6 text-purple-600" />
          </div>
        </div>
        <div class="mt-3 h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
          <div class="h-full bg-gradient-to-r from-purple-400 to-purple-500 rounded-full transition-all duration-500" :style="{ width: `${Math.min((stats.mes / 80) * 100, 100)}%` }"></div>
        </div>
      </div>

      <div class="group bg-white dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Com Falta</p>
            <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">{{ stats.com_falta || 0 }}</p>
            <p class="text-xs text-slate-400 mt-1">alunos com falta</p>
          </div>
          <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center transition-all duration-300 group-hover:scale-110">
            <Icon name="heroicons:exclamation-triangle-20-solid" class="w-6 h-6 text-amber-600" />
          </div>
        </div>
        <div class="mt-3 h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
          <div class="h-full bg-gradient-to-r from-amber-400 to-amber-500 rounded-full transition-all duration-500" :style="{ width: `${Math.min((stats.com_falta / 30) * 100, 100)}%` }"></div>
        </div>
      </div>
    </div>

    <!-- Gráfico de Barras Melhorado -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-lg font-bold text-slate-800 dark:text-white">Movimento dos Últimos 7 Dias</h2>
          <p class="text-xs text-slate-400 mt-0.5">Autorizações recebidas por dia</p>
        </div>
        <button @click="loadData" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-all duration-300" :class="{ 'animate-spin': loading }">
          <Icon name="heroicons:arrow-path-20-solid" class="w-5 h-5 text-slate-400" />
        </button>
      </div>
      <div class="h-72">
        <canvas ref="barChartCanvas"></canvas>
      </div>
    </div>

    <!-- Próximas Autorizações Melhorado -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
      <div class="p-5 border-b border-slate-100 dark:border-slate-700 bg-gradient-to-r from-slate-50 to-white dark:from-slate-800/50 dark:to-slate-800">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center">
                <Icon name="heroicons:clock-20-solid" class="w-4 h-4 text-blue-600" />
              </div>
              <h2 class="text-lg font-bold text-slate-800 dark:text-white">📋 Autorizações de Hoje</h2>
            </div>
            <p class="text-xs text-slate-400 mt-1">Lista de autorizações programadas para hoje</p>
          </div>
          <div class="flex gap-2">
            <NuxtLink to="/professor/history" class="px-4 py-2 text-sm bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 rounded-xl transition-all flex items-center gap-1 font-medium">
              Ver Histórico Completo
              <Icon name="heroicons:arrow-right-20-solid" class="w-4 h-4" />
            </NuxtLink>
          </div>
        </div>
      </div>

      <div v-if="loading" class="text-center py-16">
        <div class="inline-flex flex-col items-center gap-3">
          <Icon name="heroicons:arrow-path-20-solid" class="w-10 h-10 animate-spin text-slate-400" />
          <p class="text-slate-500">Carregando autorizações...</p>
        </div>
      </div>

      <div v-else-if="hoje.length === 0" class="text-center py-16">
        <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center mx-auto mb-4">
          <Icon name="heroicons:check-circle-20-solid" class="w-10 h-10 text-slate-400" />
        </div>
        <p class="text-slate-500 font-medium">Nenhuma autorização programada para hoje</p>
        <p class="text-xs text-slate-400 mt-1">As autorizações aparecerão aqui quando forem criadas</p>
      </div>

      <div v-else class="divide-y divide-slate-100 dark:divide-slate-700">
        <div v-for="auth in hoje.slice(0, 5)" :key="auth.id" class="p-5 hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-all duration-200">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex-1">
              <div class="flex items-center gap-2 flex-wrap mb-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-500/20 dark:to-blue-600/20 flex items-center justify-center">
                  <Icon name="heroicons:user-20-solid" class="w-4 h-4 text-blue-600" />
                </div>
                <h3 class="font-bold text-slate-900 dark:text-white">{{ auth.aluno_nome }}</h3>
                <span class="px-2 py-0.5 text-xs font-medium bg-slate-100 dark:bg-slate-700 rounded-full">{{ auth.turma }}</span>
                <span class="px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 rounded-full">
                  {{ auth.aula_numero }}ª Aula
                </span>
              </div>
              <p v-if="auth.motivo_saida" class="text-sm text-slate-500 mt-1 flex items-center gap-1">
                <Icon name="heroicons:chat-bubble-left-20-solid" class="w-3 h-3" />
                {{ auth.motivo_saida }}
              </p>
              <p class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                <Icon name="heroicons:calendar-20-solid" class="w-3 h-3" />
                Criado em: {{ formatDate(auth.created_at) }}
              </p>
            </div>
            <div class="flex items-center gap-4">
              <div class="text-right">
                <p class="text-xl font-bold font-mono text-slate-800 dark:text-white">{{ formatTime(auth.horario_saida) }}</p>
                <span :class="statusBadge(auth.status)" class="px-2 py-1 text-xs font-medium rounded-full inline-block mt-1">
                  {{ statusText(auth.status) }}
                </span>
              </div>
              <button @click="viewDetails(auth)" class="p-2.5 rounded-xl text-slate-400 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-all duration-200">
                <Icon name="heroicons:eye-20-solid" class="w-5 h-5" />
              </button>
            </div>
          </div>
        </div>
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
                    <p class="font-semibold text-slate-900 dark:text-white">{{ selectedAuth.turma }}</p>
                  </div>
                  <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-3">
                    <p class="text-xs text-slate-500">Tipo</p>
                    <p class="font-semibold capitalize" :class="selectedAuth.tipo === 'entrada' ? 'text-emerald-600' : 'text-blue-600'">
                      {{ selectedAuth.tipo === 'entrada' ? 'Entrada' : 'Saída' }}
                    </p>
                  </div>
                  <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-3">
                    <p class="text-xs text-slate-500">Horário</p>
                    <p class="font-semibold font-mono text-slate-900 dark:text-white">{{ formatTime(selectedAuth.horario_saida) }}</p>
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
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useApi } from '~/composables/useApi'
import Chart from 'chart.js/auto'

definePageMeta({
  middleware: 'auth',
  layout: 'professor'
})

const { get } = useApi()

const loading = ref(false)
const user = ref(null)
const stats = ref({
  total: 0,
  hoje: 0,
  mes: 0,
  com_falta: 0
})
const authorizations = ref([])
const dailyStats = ref([])

const currentTime = ref('')
const currentDate = ref('')
const showDetailsModal = ref(false)
const selectedAuth = ref(null)
const toastMessage = ref('')
const toastVariant = ref('success')

const barChartCanvas = ref(null)
let barChart = null
let timeInterval = null

const hoje = computed(() => {
  const today = new Date().toDateString()
  return authorizations.value.filter(auth => {
    const authDate = new Date(auth.created_at).toDateString()
    return authDate === today
  })
})

const showToast = (message, variant = 'success') => {
  toastMessage.value = message
  toastVariant.value = variant
  setTimeout(() => {
    toastMessage.value = ''
  }, 3000)
}

const loadUser = async () => {
  try {
    const response = await get('/user/profile')
    user.value = response
  } catch (error) {
    console.error('Erro ao carregar usuário:', error)
  }
}

const loadData = async () => {
  loading.value = true
  try {
    const statsResponse = await get('/professor/authorizations/stats')
    stats.value = statsResponse
    
    const authResponse = await get('/professor/authorizations/history', { per_page: 50 })
    authorizations.value = authResponse.data || authResponse
    
    const dailyResponse = await get('/professor/authorizations/daily')
    dailyStats.value = dailyResponse
    
    renderChart()
  } catch (error) {
    console.error('Erro ao carregar dados:', error)
    showToast('Erro ao carregar dados', 'error')
  } finally {
    loading.value = false
  }
}

const renderChart = () => {
  if (!barChartCanvas.value || dailyStats.value.length === 0) return
  
  if (barChart) barChart.destroy()
  
  const ctx = barChartCanvas.value.getContext('2d')
  barChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: dailyStats.value.map(d => d.day),
      datasets: [{
        label: 'Autorizações',
        data: dailyStats.value.map(d => d.total),
        backgroundColor: '#3b82f6',
        borderRadius: 8,
        barPercentage: 0.7,
        categoryPercentage: 0.8
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      plugins: {
        legend: { display: false },
        tooltip: { 
          callbacks: { 
            label: (ctx) => `${ctx.raw} autorizações` 
          } 
        }
      },
      scales: {
        y: { 
          beginAtZero: true, 
          ticks: { stepSize: 1, precision: 0 },
          grid: { color: '#e2e8f0' }
        },
        x: { grid: { display: false } }
      }
    }
  })
}

const viewDetails = (auth) => {
  selectedAuth.value = auth
  showDetailsModal.value = true
}

const updateDateTime = () => {
  const now = new Date()
  currentTime.value = now.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit', second: '2-digit' })
  currentDate.value = now.toLocaleDateString('pt-BR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
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

onMounted(() => {
  loadUser()
  loadData()
  updateDateTime()
  timeInterval = setInterval(updateDateTime, 1000)
})

onUnmounted(() => {
  if (timeInterval) clearInterval(timeInterval)
  if (barChart) barChart.destroy()
})
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