<template>
  <div>
    <!-- Hero Section com Saudação Personalizada -->
    <div class="mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <div class="flex items-center gap-3 mb-2">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-500 to-slate-600 flex items-center justify-center shadow-lg shadow-slate-500/20">
              <Icon name="heroicons:home-20-solid" class="w-6 h-6 text-white" />
            </div>
            <div>
              <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Dashboard do Professor</h1>
              <p class="text-slate-500 dark:text-slate-400">
                Bem-vindo(a), <span class="font-semibold text-slate-700 dark:text-slate-300">{{ user?.name || 'Professor(a)' }}</span>
              </p>
            </div>
          </div>
        </div>
        
        <!-- Relógio ao vivo -->
        <div class="bg-white dark:bg-slate-800 rounded-xl px-4 py-2 border border-slate-200 dark:border-slate-700 shadow-sm">
          <div class="flex items-center gap-2">
            <Icon name="heroicons:clock-20-solid" class="w-5 h-5 text-slate-400" />
            <span class="font-mono text-lg font-bold text-slate-700 dark:text-slate-300">{{ currentTime }}</span>
          </div>
          <p class="text-xs text-slate-400">{{ currentDate }}</p>
        </div>
      </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div 
        v-for="(stat, index) in statCards" 
        :key="stat.key"
        class="group bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 cursor-pointer"
        :style="{ animationDelay: `${index * 0.1}s` }"
        @click="stat.action && navigateTo(stat.action)"
      >
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">{{ stat.label }}</p>
            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ stats[stat.key] || 0 }}</p>
            <p v-if="stat.subtext" class="text-xs text-slate-400 mt-1">{{ stat.subtext }}</p>
          </div>
          <div :class="[
            'w-14 h-14 rounded-2xl flex items-center justify-center transition-all duration-300 group-hover:scale-110',
            stat.bgColor
          ]">
            <Icon :name="stat.icon" class="w-7 h-7" :class="stat.iconColor" />
          </div>
        </div>
      </div>
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <!-- Gráfico de Rosquinha -->
      <AppCard>
        <template #header>
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-lg font-bold text-slate-800 dark:text-white">Suas Autorizações</h2>
              <p class="text-xs text-slate-400 mt-1">{{ currentMonth }}</p>
            </div>
            <button @click="loadStats" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
              <Icon name="heroicons:arrow-path-20-solid" class="w-4 h-4 text-slate-400" :class="{ 'animate-spin': loading }" />
            </button>
          </div>
        </template>
        
        <div class="flex flex-col lg:flex-row items-center gap-6">
          <div class="relative w-48 h-48">
            <canvas ref="donutChartCanvas"></canvas>
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="text-center">
                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ stats.total_mes || 0 }}</p>
                <p class="text-xs text-slate-500">Total no mês</p>
              </div>
            </div>
          </div>
          
          <div class="flex-1 space-y-2">
            <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
              <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                <span class="text-sm text-slate-600 dark:text-slate-400">Pendentes</span>
              </div>
              <span class="font-bold text-slate-900 dark:text-white">{{ stats.pendentes || 0 }}</span>
            </div>
            <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
              <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                <span class="text-sm text-slate-600 dark:text-slate-400">Aprovadas</span>
              </div>
              <span class="font-bold text-slate-900 dark:text-white">{{ stats.aprovadas || 0 }}</span>
            </div>
            <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
              <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                <span class="text-sm text-slate-600 dark:text-slate-400">Rejeitadas</span>
              </div>
              <span class="font-bold text-slate-900 dark:text-white">{{ stats.rejeitadas || 0 }}</span>
            </div>
          </div>
        </div>
      </AppCard>

      <!-- Gráfico de Barras -->
      <AppCard>
        <template #header>
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white">Movimento dos Últimos 7 Dias</h2>
            <span class="text-xs text-slate-400">Autorizações recebidas</span>
          </div>
        </template>
        <div class="h-64">
          <canvas ref="barChartCanvas"></canvas>
        </div>
      </AppCard>
    </div>

    <!-- Próximas Saídas Hoje -->
    <AppCard>
      <template #header>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h2 class="text-lg font-bold text-slate-800 dark:text-white">⏰ Próximas Saídas Hoje</h2>
            <p class="text-xs text-slate-400 mt-1">Autorizações aguardando sua validação</p>
          </div>
          <NuxtLink to="/professor/authorizations" class="text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 flex items-center gap-1">
            Ver todas pendentes
            <Icon name="heroicons:arrow-right-20-solid" class="w-4 h-4" />
          </NuxtLink>
        </div>
      </template>

      <div v-if="loadingHoje" class="text-center py-12">
        <Icon name="heroicons:arrow-path-20-solid" class="w-8 h-8 animate-spin mx-auto text-slate-400" />
      </div>

      <div v-else-if="hoje.length === 0" class="text-center py-12">
        <Icon name="heroicons:check-circle-20-solid" class="w-16 h-16 mx-auto mb-3 text-green-500 opacity-50" />
        <p class="text-slate-500 dark:text-slate-400">Nenhuma saída programada para hoje</p>
        <p class="text-xs text-slate-400 mt-1">Ótimo! Todas as autorizações já foram processadas</p>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div 
          v-for="auth in hoje.slice(0, 4)" 
          :key="auth.id" 
          class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl hover:shadow-md transition-all group"
        >
          <div class="flex-1">
            <div class="flex items-center gap-2 mb-1">
              <p class="font-semibold text-slate-900 dark:text-white">{{ auth.aluno_nome }}</p>
              <span class="px-2 py-0.5 text-[10px] font-semibold bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-400 rounded-full">
                {{ auth.aula_numero }}ª Aula
              </span>
            </div>
            <p class="text-sm text-slate-500">{{ auth.turma }}</p>
            <p v-if="auth.motivo_saida" class="text-xs text-slate-400 mt-1 truncate">{{ auth.motivo_saida }}</p>
          </div>
          <div class="text-right">
            <p class="text-lg font-bold text-slate-800 dark:text-white">{{ formatTime(auth.horario_saida) }}</p>
            <button 
              @click="openApproveModal(auth)"
              class="mt-2 px-3 py-1.5 text-xs bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors flex items-center gap-1"
            >
              <Icon name="heroicons:check-20-solid" class="w-3 h-3" />
              Validar
            </button>
          </div>
        </div>
      </div>
    </AppCard>

    <!-- Modal de Aprovação Rápida -->
    <AppModal v-model="showApproveModal" :title="`Validar Autorização - ${selectedAuth?.aluno_nome || ''}`" size="md">
      <div v-if="selectedAuth" class="space-y-4">
        <div class="grid grid-cols-2 gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-xl">
          <div>
            <p class="text-xs text-slate-500">Aluno</p>
            <p class="font-semibold text-slate-900 dark:text-white">{{ selectedAuth.aluno_nome }}</p>
          </div>
          <div>
            <p class="text-xs text-slate-500">Turma</p>
            <p class="font-semibold text-slate-900 dark:text-white">{{ selectedAuth.turma }}</p>
          </div>
          <div>
            <p class="text-xs text-slate-500">Horário</p>
            <p class="font-semibold text-slate-900 dark:text-white">{{ formatTime(selectedAuth.horario_saida) }}</p>
          </div>
          <div>
            <p class="text-xs text-slate-500">Aula</p>
            <p class="font-semibold text-slate-900 dark:text-white">{{ selectedAuth.aula_numero }}ª Aula</p>
          </div>
        </div>

        <div v-if="selectedAuth.motivo_saida" class="p-3 bg-slate-50 dark:bg-slate-800 rounded-lg">
          <p class="text-xs text-slate-500">Motivo da Saída</p>
          <p class="text-sm text-slate-700 dark:text-slate-300">{{ selectedAuth.motivo_saida }}</p>
        </div>

        <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
          <label class="flex items-center gap-3 cursor-pointer p-3 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition-colors">
            <input type="checkbox" v-model="approveForm.com_falta" class="w-4 h-4 rounded border-slate-300 text-red-500 focus:ring-red-500">
            <div>
              <span class="text-slate-700 dark:text-slate-300 font-medium">Marcar como COM FALTA</span>
              <p class="text-xs text-slate-400">O aluno terá falta registrada nesta aula</p>
            </div>
          </label>
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Observação (opcional)</label>
          <textarea 
            v-model="approveForm.observacao" 
            rows="3" 
            class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
            placeholder="Adicione uma observação sobre esta autorização..."
          ></textarea>
        </div>

        <div class="flex gap-3 pt-4">
          <button @click="showApproveModal = false" class="flex-1 px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            Cancelar
          </button>
          <button @click="confirmApprove" :disabled="submitting" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors disabled:opacity-50 flex items-center justify-center gap-2">
            <Icon v-if="submitting" name="heroicons:arrow-path-20-solid" class="w-4 h-4 animate-spin" />
            {{ submitting ? 'Processando...' : 'Confirmar Aprovação' }}
          </button>
        </div>
      </div>
    </AppModal>

    <AppToast v-if="toastMessage" :message="toastMessage" :variant="toastVariant" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useApi } from '~/composables/useApi'
import { useAuthorizations } from '~/composables/useAuthorizations'
import { useToast } from '~/composables/useToast'
import AppCard from '~/components/common/AppCard.vue'
import AppModal from '~/components/common/AppModal.vue'

definePageMeta({
  middleware: 'auth',
  layout: 'professor'
})

const { get } = useApi()
const { approve, fetchPending } = useAuthorizations()
const { show: toast } = useToast()

const loading = ref(false)
const loadingHoje = ref(false)
const submitting = ref(false)

const user = ref(null)
const stats = ref({
  pendentes: 0,
  aprovadas_hoje: 0,
  rejeitadas_hoje: 0,
  total_mes: 0,
  aprovadas: 0,
  rejeitadas: 0
})

const pendingAuthorizations = ref([])
const dailyStats = ref([])
const currentTime = ref('')
const currentDate = ref('')

const showApproveModal = ref(false)
const selectedAuth = ref(null)
const approveForm = ref({ com_falta: false, observacao: '' })

const donutChartCanvas = ref(null)
const barChartCanvas = ref(null)
let donutChart = null
let barChart = null
let timeInterval = null

const statCards = [
  { key: 'pendentes', label: 'Autorizações Pendentes', icon: 'heroicons:clock-20-solid', bgColor: 'bg-yellow-100 dark:bg-yellow-500/20', iconColor: 'text-yellow-600 dark:text-yellow-400', action: '/professor/authorizations', subtext: 'Aguardando sua validação' },
  { key: 'aprovadas_hoje', label: 'Aprovadas Hoje', icon: 'heroicons:check-badge-20-solid', bgColor: 'bg-green-100 dark:bg-green-500/20', iconColor: 'text-green-600 dark:text-green-400', subtext: 'Autorizações confirmadas' },
  { key: 'rejeitadas_hoje', label: 'Rejeitadas Hoje', icon: 'heroicons:x-mark-20-solid', bgColor: 'bg-red-100 dark:bg-red-500/20', iconColor: 'text-red-600 dark:text-red-400', subtext: 'Solicitações negadas' },
  { key: 'total_mes', label: 'Total no Mês', icon: 'heroicons:calendar-20-solid', bgColor: 'bg-blue-100 dark:bg-blue-500/20', iconColor: 'text-blue-600 dark:text-blue-400', subtext: 'Autorizações recebidas' }
]

const currentMonth = computed(() => {
  return new Date().toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' })
})

const hoje = computed(() => {
  const today = new Date().toDateString()
  return pendingAuthorizations.value.filter(auth => {
    const authDate = new Date(auth.created_at).toDateString()
    return authDate === today
  })
})

const loadUser = async () => {
  try {
    const response = await get('/user/profile')
    user.value = response
  } catch (error) {
    console.error('Erro ao carregar usuário:', error)
  }
}

const loadStats = async () => {
  loading.value = true
  try {
    const response = await get('/professor/authorizations/stats')
    stats.value = response
    
    const dailyResponse = await get('/professor/authorizations/daily')
    dailyStats.value = dailyResponse
    
    renderCharts()
  } catch (error) {
    console.error('Erro ao carregar estatísticas:', error)
    toast('Erro ao carregar estatísticas', 'error')
  } finally {
    loading.value = false
  }
}

const loadPending = async () => {
  loadingHoje.value = true
  try {
    const response = await fetchPending()
    pendingAuthorizations.value = response
  } catch (error) {
    console.error('Erro ao carregar pendentes:', error)
  } finally {
    loadingHoje.value = false
  }
}

const renderCharts = () => {
  if (donutChartCanvas.value) {
    if (donutChart) donutChart.destroy()
    
    const ctx = donutChartCanvas.value.getContext('2d')
    donutChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Pendentes', 'Aprovadas', 'Rejeitadas'],
        datasets: [{
          data: [stats.value.pendentes, stats.value.aprovadas, stats.value.rejeitadas],
          backgroundColor: ['#eab308', '#22c55e', '#ef4444'],
          borderWidth: 0,
          cutout: '60%',
          hoverOffset: 10
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: { display: false },
          tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${ctx.raw}` } }
        }
      }
    })
  }
  
  if (barChartCanvas.value && dailyStats.value.length > 0) {
    if (barChart) barChart.destroy()
    
    const ctx = barChartCanvas.value.getContext('2d')
    barChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: dailyStats.value.map(d => d.day),
        datasets: [{
          label: 'Autorizações',
          data: dailyStats.value.map(d => d.total),
          backgroundColor: '#64748b',
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
          tooltip: { callbacks: { label: (ctx) => `${ctx.raw} autorizações` } }
        },
        scales: {
          y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } },
          x: { grid: { display: false } }
        }
      }
    })
  }
}

const openApproveModal = (auth) => {
  selectedAuth.value = auth
  approveForm.value = { com_falta: false, observacao: '' }
  showApproveModal.value = true
}

const confirmApprove = async () => {
  if (!selectedAuth.value) return
  
  submitting.value = true
  try {
    await approve(selectedAuth.value.id, approveForm.value.com_falta, approveForm.value.observacao)
    toast('✅ Autorização aprovada com sucesso! Portaria foi notificada.', 'success')
    showApproveModal.value = false
    await Promise.all([loadStats(), loadPending()])
  } catch (error) {
    const message = error.response?.data?.message || 'Erro ao aprovar autorização'
    toast(message, 'error')
  } finally {
    submitting.value = false
  }
}

const updateDateTime = () => {
  const now = new Date()
  currentTime.value = now.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit', second: '2-digit' })
  currentDate.value = now.toLocaleDateString('pt-BR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
}

const formatTime = (time) => {
  if (!time) return '—'
  return time.substring(0, 5)
}

onMounted(() => {
  loadUser()
  loadStats()
  loadPending()
  updateDateTime()
  timeInterval = setInterval(updateDateTime, 1000)
})

onUnmounted(() => {
  if (timeInterval) clearInterval(timeInterval)
})
</script>

<style scoped>
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.group {
  animation: fadeInUp 0.5s ease-out forwards;
  opacity: 0;
}
</style>