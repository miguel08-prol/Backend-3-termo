<template>
  <div>
    <!-- Hero Section -->
    <div class="mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <div class="flex items-center gap-3 mb-2">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
              <Icon name="heroicons:home-20-solid" class="w-6 h-6 text-white" />
            </div>
            <div>
              <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Dashboard - Portaria</h1>
              <p class="text-slate-500 dark:text-slate-400">
                Bem-vindo(a), <span class="font-semibold text-slate-700 dark:text-slate-300">{{ user?.name || 'Portaria' }}</span>
              </p>
            </div>
          </div>
        </div>
        
        <!-- Scanner/RFID Simulado (opcional) -->
        <div class="bg-white dark:bg-slate-800 rounded-xl px-4 py-2 border border-slate-200 dark:border-slate-700 shadow-sm">
          <div class="flex items-center gap-2">
            <Icon name="heroicons:qr-code-20-solid" class="w-5 h-5 text-emerald-500" />
            <span class="text-sm text-slate-600 dark:text-slate-300">Leitor de QR Code</span>
            <button @click="simulateScan" class="ml-2 px-2 py-1 text-xs bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 rounded-lg">
              Simular
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
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

    <!-- Próximas Saídas -->
    <AppCard>
      <template #header>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h2 class="text-lg font-bold text-slate-800 dark:text-white">🚪 Próximas Saídas</h2>
            <p class="text-xs text-slate-400 mt-1">Autorizações aprovadas aguardando saída</p>
          </div>
          <div class="flex gap-2">
            <div class="relative">
              <input 
                v-model="searchTerm" 
                type="text" 
                placeholder="Buscar aluno ou turma..." 
                class="pl-10 pr-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500"
                @keyup.enter="search"
              />
              <Icon name="heroicons:magnifying-glass-20-solid" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
            </div>
            <NuxtLink to="/gateway/exits" class="text-sm text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 flex items-center gap-1">
              Ver todas
              <Icon name="heroicons:arrow-right-20-solid" class="w-4 h-4" />
            </NuxtLink>
          </div>
        </div>
      </template>

      <div v-if="loading" class="flex justify-center py-12">
        <Icon name="heroicons:arrow-path-20-solid" class="w-8 h-8 animate-spin text-slate-400" />
      </div>

      <div v-else-if="pendingExits.length === 0" class="text-center py-12">
        <Icon name="heroicons:check-circle-20-solid" class="w-16 h-16 mx-auto mb-3 text-emerald-500 opacity-50" />
        <p class="text-slate-500 dark:text-slate-400">Nenhuma saída pendente no momento</p>
        <p class="text-xs text-slate-400 mt-1">Todas as autorizações já foram processadas</p>
      </div>

      <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div 
          v-for="auth in pendingExits.slice(0, 4)" 
          :key="auth.id" 
          class="flex items-center justify-between p-4 bg-gradient-to-r from-slate-50 to-white dark:from-slate-800/50 dark:to-slate-800 rounded-xl hover:shadow-md transition-all group border border-slate-100 dark:border-slate-700"
        >
          <div class="flex-1">
            <div class="flex items-center gap-2 mb-1">
              <p class="font-semibold text-slate-900 dark:text-white">{{ auth.aluno_nome }}</p>
              <span :class="[
                'px-2 py-0.5 text-[10px] font-semibold rounded-full',
                isLate(auth) ? 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400'
              ]">
                {{ isLate(auth) ? 'Atrasado' : 'No horário' }}
              </span>
            </div>
            <p class="text-sm text-slate-500">{{ auth.turma }}</p>
            <div class="flex items-center gap-3 mt-1 text-xs text-slate-400">
              <span class="flex items-center gap-1">
                <Icon name="heroicons:academic-cap-20-solid" class="w-3 h-3" />
                {{ auth.aula_numero }}ª Aula
              </span>
              <span class="flex items-center gap-1">
                <Icon name="heroicons:user-20-solid" class="w-3 h-3" />
                {{ auth.professor?.name?.split(' ')[0] || '—' }}
              </span>
            </div>
          </div>
          <div class="text-right">
            <p class="text-xl font-bold" :class="isLate(auth) ? 'text-red-500' : 'text-emerald-600 dark:text-emerald-400'">
              {{ formatTime(auth.horario_saida) }}
            </p>
            <p class="text-xs text-slate-400 mb-2">{{ formatRelativeTime(auth.horario_saida) }}</p>
            <button 
              @click="openExitModal(auth)"
              class="px-4 py-1.5 text-sm bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors flex items-center gap-1 shadow-sm"
            >
              <Icon name="heroicons:arrow-right-start-on-rectangle-20-solid" class="w-4 h-4" />
              Registrar Saída
            </button>
          </div>
        </div>
      </div>
    </AppCard>

    <!-- Atividade Recente -->
    <AppCard class="mt-6">
      <template #header>
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-bold text-slate-800 dark:text-white">📋 Atividade Recente</h2>
          <NuxtLink to="/gateway/history" class="text-sm text-emerald-600 hover:text-emerald-700">Ver histórico completo →</NuxtLink>
        </div>
      </template>

      <div v-if="loadingHistory" class="text-center py-8">
        <Icon name="heroicons:arrow-path-20-solid" class="w-6 h-6 animate-spin mx-auto text-slate-400" />
      </div>

      <div v-else-if="recentHistory.length === 0" class="text-center py-8 text-slate-400">
        Nenhuma atividade registrada hoje
      </div>

      <div v-else class="space-y-3">
        <div v-for="entry in recentHistory" :key="entry.id" class="flex items-center gap-3 p-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 rounded-lg transition-colors">
          <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center">
            <Icon name="heroicons:check-20-solid" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
          </div>
          <div class="flex-1">
            <p class="text-sm font-medium text-slate-900 dark:text-white">
              {{ entry.authorization?.aluno_nome }} - {{ entry.authorization?.turma }}
            </p>
            <p class="text-xs text-slate-400">
              Saída registrada por {{ entry.portaria?.name }} • {{ formatTimeAgo(entry.created_at) }}
            </p>
          </div>
          <div class="text-right">
            <p class="text-sm font-mono text-slate-600 dark:text-slate-300">
              {{ formatTime(entry.horario_saida) }}
            </p>
          </div>
        </div>
      </div>
    </AppCard>

    <!-- Modal de Registro de Saída -->
    <AppModal v-model="showExitModal" title="Registrar Saída" size="md">
      <div v-if="selectedAuth" class="space-y-4">
        <div class="grid grid-cols-2 gap-3 p-4 bg-gradient-to-r from-emerald-50 to-transparent dark:from-emerald-500/10 rounded-xl">
          <div>
            <p class="text-xs text-emerald-600 dark:text-emerald-400">Aluno</p>
            <p class="font-semibold text-slate-900 dark:text-white text-lg">{{ selectedAuth.aluno_nome }}</p>
          </div>
          <div>
            <p class="text-xs text-emerald-600 dark:text-emerald-400">Turma</p>
            <p class="font-semibold text-slate-900 dark:text-white">{{ selectedAuth.turma }}</p>
          </div>
          <div>
            <p class="text-xs text-emerald-600 dark:text-emerald-400">Horário Autorizado</p>
            <p class="font-semibold text-slate-900 dark:text-white">{{ formatTime(selectedAuth.horario_saida) }}</p>
          </div>
          <div>
            <p class="text-xs text-emerald-600 dark:text-emerald-400">Professor</p>
            <p class="font-semibold text-slate-900 dark:text-white">{{ selectedAuth.professor?.name || '—' }}</p>
          </div>
        </div>

        <div v-if="selectedAuth.motivo_saida" class="p-3 bg-slate-50 dark:bg-slate-800 rounded-lg">
          <p class="text-xs text-slate-500">Motivo da Saída</p>
          <p class="text-sm text-slate-700 dark:text-slate-300">{{ selectedAuth.motivo_saida }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Observações (opcional)</label>
          <textarea 
            v-model="exitForm.observacoes" 
            rows="3" 
            class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 transition-all"
            placeholder="Registre alguma observação sobre esta saída..."
          ></textarea>
        </div>

        <div class="bg-amber-50 dark:bg-amber-500/10 p-3 rounded-lg">
          <div class="flex items-center gap-2 text-amber-700 dark:text-amber-400">
            <Icon name="heroicons:information-circle-20-solid" class="w-5 h-5" />
            <span class="text-sm font-medium">Notificação</span>
          </div>
          <p class="text-xs text-amber-600 dark:text-amber-300 mt-1">
            O responsável será notificado por e-mail e WhatsApp assim que a saída for confirmada.
          </p>
        </div>

        <div class="flex gap-3 pt-4">
          <button @click="showExitModal = false" class="flex-1 px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            Cancelar
          </button>
          <button @click="confirmExit" :disabled="submitting" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors disabled:opacity-50 flex items-center justify-center gap-2">
            <Icon v-if="submitting" name="heroicons:arrow-path-20-solid" class="w-4 h-4 animate-spin" />
            {{ submitting ? 'Registrando...' : 'Confirmar Saída' }}
          </button>
        </div>
      </div>
    </AppModal>

    <!-- Modal de Scanner -->
    <AppModal v-model="showScanModal" title="Leitor de QR Code" size="sm">
      <div class="text-center py-8">
        <div class="relative w-48 h-48 mx-auto mb-4">
          <div class="absolute inset-0 border-2 border-emerald-500 rounded-lg animate-pulse"></div>
          <div class="absolute inset-4 border-2 border-emerald-500 rounded-lg"></div>
          <div class="absolute inset-0 flex items-center justify-center">
            <Icon name="heroicons:camera-20-solid" class="w-12 h-12 text-emerald-500 animate-pulse" />
          </div>
        </div>
        <p class="text-slate-600 dark:text-slate-300">Aponte a câmera para o QR Code do aluno</p>
        <p class="text-xs text-slate-400 mt-2">Simulação: digite o nome do aluno para buscar</p>
        <input 
          v-model="scanCode" 
          type="text" 
          placeholder="Código ou nome do aluno" 
          class="mt-4 w-full px-4 py-2 border rounded-lg"
          @keyup.enter="processScan"
        />
        <button @click="processScan" class="mt-4 px-6 py-2 bg-emerald-600 text-white rounded-lg w-full">
          Buscar
        </button>
      </div>
    </AppModal>

    <AppToast v-if="toastMessage" :message="toastMessage" :variant="toastVariant" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useGateway } from '~/composables/useGateway'
import { useApi } from '~/composables/useApi'
import { useToast } from '~/composables/useToast'
import AppCard from '~/components/common/AppCard.vue'
import AppModal from '~/components/common/AppModal.vue'

definePageMeta({
  middleware: 'auth',
  layout: 'gateway'
})

const { get } = useApi()
const { pendingExits, loading, fetchPendingExits, registerExit,fetchHistory } = useGateway()
const { show: toast } = useToast()

const user = ref(null)
const stats = ref({
  pendentes: 0,
  hoje: 0,
  total_mes: 0,
  com_falta: 0
})
const recentHistory = ref([])
const loadingHistory = ref(false)
const searchTerm = ref('')
const showExitModal = ref(false)
const showScanModal = ref(false)
const selectedAuth = ref(null)
const submitting = ref(false)
const scanCode = ref('')
const exitForm = ref({ observacoes: '' })

const statCards = [
  { key: 'pendentes', label: 'Saídas Pendentes', icon: 'heroicons:clock-20-solid', bgColor: 'bg-yellow-100 dark:bg-yellow-500/20', iconColor: 'text-yellow-600 dark:text-yellow-400', action: '/gateway/exits', subtext: 'Aguardando registro' },
  { key: 'hoje', label: 'Saídas Hoje', icon: 'heroicons:check-badge-20-solid', bgColor: 'bg-green-100 dark:bg-green-500/20', iconColor: 'text-green-600 dark:text-green-400', subtext: 'Registradas hoje' },
  { key: 'total_mes', label: 'Total no Mês', icon: 'heroicons:calendar-20-solid', bgColor: 'bg-blue-100 dark:bg-blue-500/20', iconColor: 'text-blue-600 dark:text-blue-400', subtext: 'Saídas realizadas' },
  { key: 'com_falta', label: 'Com Falta', icon: 'heroicons:exclamation-triangle-20-solid', bgColor: 'bg-red-100 dark:bg-red-500/20', iconColor: 'text-red-600 dark:text-red-400', subtext: 'Alunos com falta' }
]

const loadUser = async () => {
  try {
    const response = await get('/user/profile')
    user.value = response
  } catch (error) {
    console.error('Erro ao carregar usuário:', error)
  }
}

const loadStats = async () => {
  try {
    const response = await get('/gateway/stats')
    stats.value = response
  } catch (error) {
    console.error('Erro ao carregar estatísticas:', error)
  }
}

const loadRecentHistory = async () => {
  loadingHistory.value = true
  try {
    const response = await fetchHistory({ per_page: 5 })
    recentHistory.value = response.data || response
  } catch (error) {
    console.error('Erro ao carregar histórico:', error)
  } finally {
    loadingHistory.value = false
  }
}

const loadData = async () => {
  await Promise.all([
    fetchPendingExits(),
    loadStats(),
    loadRecentHistory()
  ])
}

const search = async () => {
  if (searchTerm.value.length < 3) {
    await fetchPendingExits()
    return
  }
  const results = await search(searchTerm.value)
  pendingExits.value = results
}

const openExitModal = (auth) => {
  selectedAuth.value = auth
  exitForm.value = { observacoes: '' }
  showExitModal.value = true
}

const confirmExit = async () => {
  if (!selectedAuth.value) return
  
  submitting.value = true
  try {
    await registerExit(selectedAuth.value.id, exitForm.value.observacoes)
    toast('✅ Saída registrada com sucesso! Responsável foi notificado.', 'success')
    showExitModal.value = false
    await loadData()
  } catch (error) {
    const message = error.response?.data?.message || 'Erro ao registrar saída'
    toast(message, 'error')
  } finally {
    submitting.value = false
  }
}

const simulateScan = () => {
  scanCode.value = ''
  showScanModal.value = true
}

const processScan = async () => {
  if (!scanCode.value) {
    toast('Digite o nome do aluno ou código', 'error')
    return
  }
  
  showScanModal.value = false
  const results = await search(scanCode.value)
  if (results && results.length > 0) {
    openExitModal(results[0])
  } else {
    toast('Nenhum aluno encontrado com este código', 'error')
  }
  scanCode.value = ''
}

const isLate = (auth) => {
  if (!auth.horario_saida) return false
  const [hour, minute] = auth.horario_saida.split(':')
  const authTime = new Date()
  authTime.setHours(parseInt(hour), parseInt(minute), 0)
  const now = new Date()
  return now > authTime
}

const formatTime = (time) => {
  if (!time) return '—'
  return time.substring(0, 5)
}

const formatRelativeTime = (time) => {
  if (!time) return ''
  const [hour, minute] = time.split(':')
  const authTime = new Date()
  authTime.setHours(parseInt(hour), parseInt(minute), 0)
  const now = new Date()
  const diffMinutes = Math.floor((authTime - now) / 60000)
  
  if (diffMinutes < 0) return `${Math.abs(diffMinutes)} min atrasado`
  if (diffMinutes === 0) return 'agora'
  if (diffMinutes < 60) return `em ${diffMinutes} min`
  return `em ${Math.floor(diffMinutes / 60)}h`
}

const formatTimeAgo = (date) => {
  if (!date) return ''
  const diff = Math.floor((new Date() - new Date(date)) / 1000 / 60)
  if (diff < 1) return 'agora mesmo'
  if (diff < 60) return `${diff} min atrás`
  if (diff < 1440) return `${Math.floor(diff / 60)} horas atrás`
  return `${Math.floor(diff / 1440)} dias atrás`
}

onMounted(() => {
  loadUser()
  loadData()
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