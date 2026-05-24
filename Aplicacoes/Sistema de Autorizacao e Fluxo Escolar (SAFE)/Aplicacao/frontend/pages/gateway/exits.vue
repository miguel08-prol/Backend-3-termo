<template>
  <div class="space-y-8 animate-fadeIn">
    <!-- Header Premium -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-600 via-emerald-700 to-teal-800 p-8 text-white shadow-2xl">
      <div class="absolute top-0 right-0 w-80 h-80 bg-white/5 rounded-full -mr-40 -mt-40 blur-2xl"></div>
      <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full -ml-32 -mb-32 blur-2xl"></div>
      
      <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-5">
          <div class="w-16 h-16 rounded-2xl bg-white/15 backdrop-blur-md flex items-center justify-center shadow-xl ring-1 ring-white/20">
            <Icon name="heroicons:clock-20-solid" class="w-8 h-8" />
          </div>
          <div>
            <h1 class="text-3xl font-black tracking-tight">Saídas Pendentes</h1>
            <div class="flex items-center gap-2 mt-2">
              <div class="flex items-center gap-1.5">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-yellow-500"></span>
                </span>
                <span class="text-emerald-100 text-sm font-medium">{{ pendingExits.length }} aguardando</span>
              </div>
              <div class="w-px h-4 bg-white/30"></div>
              <span class="text-emerald-100/80 text-sm">Autorizações aprovadas</span>
            </div>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <div class="bg-white/10 backdrop-blur-md rounded-2xl px-5 py-2.5 text-center ring-1 ring-white/20">
            <p class="text-2xl font-bold font-mono">{{ currentTime }}</p>
            <p class="text-xs text-emerald-200 mt-0.5">{{ currentDate }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Busca Premium -->
    <div class="bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm">
      <div class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
          <Icon name="heroicons:magnifying-glass-20-solid" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
          <input 
            v-model="searchTerm" 
            type="text" 
            placeholder="Buscar por nome do aluno ou turma..." 
            class="w-full pl-12 pr-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
            @keyup.enter="handleSearch"
          />
        </div>
        <button @click="handleSearch" class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white rounded-xl transition-all flex items-center gap-2 font-semibold shadow-md">
          <Icon name="heroicons:magnifying-glass-20-solid" class="w-4 h-4" />
          Buscar
        </button>
        <button @click="clearSearch" class="px-6 py-3 border-2 border-slate-300 dark:border-slate-600 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 transition-all font-medium">
          Limpar
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center py-20">
      <div class="flex flex-col items-center gap-4">
        <div class="w-16 h-16 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center">
          <Icon name="heroicons:arrow-path-20-solid" class="w-8 h-8 animate-spin text-emerald-600 dark:text-emerald-400" />
        </div>
        <p class="text-slate-500 font-medium">Carregando saídas pendentes...</p>
      </div>
    </div>

    <!-- Empty State Premium -->
    <div v-else-if="pendingExits.length === 0" class="text-center py-20 bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700">
      <div class="w-24 h-24 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center mx-auto mb-5">
        <Icon name="heroicons:check-circle-20-solid" class="w-12 h-12 text-emerald-500" />
      </div>
      <p class="text-slate-500 font-medium text-lg">Nenhuma saída pendente</p>
      <p class="text-sm text-slate-400 mt-1">Todas as autorizações já foram registradas</p>
    </div>

    <!-- Cards Grid Premium -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="auth in pendingExits" 
        :key="auth.id" 
        class="group relative bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-1.5"
      >
        <!-- Indicador de horário -->
        <div :class="[
          'absolute left-0 top-0 bottom-0 w-1.5',
          isLate(auth) ? 'bg-red-500' : 'bg-emerald-500'
        ]"></div>
        
        <!-- Card Header -->
        <div class="p-5 pb-3 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-500/20 dark:to-emerald-600/20 flex items-center justify-center">
              <Icon name="heroicons:user-20-solid" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
            </div>
            <span class="text-xs font-mono font-medium text-slate-400">#{{ auth.id }}</span>
          </div>
          <span :class="isLate(auth) ? 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400'" class="px-2.5 py-1 text-xs font-bold rounded-full">
            {{ isLate(auth) ? '⚠️ Atrasado' : '✅ No horário' }}
          </span>
        </div>
        
        <!-- Card Body -->
        <div class="p-5">
          <h3 class="font-bold text-xl text-slate-900 dark:text-white mb-1">{{ auth.aluno_nome }}</h3>
          <div class="flex items-center gap-2 mb-4">
            <span class="px-2.5 py-1 text-xs font-mono font-semibold bg-slate-100 dark:bg-slate-700 rounded-lg">{{ auth.turma }}</span>
            <span class="px-2.5 py-1 text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 rounded-lg">{{ auth.aula_numero }}ª Aula</span>
          </div>
          
          <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="p-3 bg-slate-50 dark:bg-slate-700/30 rounded-xl">
              <p class="text-xs text-slate-500 font-medium">Horário Autorizado</p>
              <p class="text-xl font-bold font-mono" :class="isLate(auth) ? 'text-red-500' : 'text-emerald-600 dark:text-emerald-400'">
                {{ formatTime(auth.horario_saida) }}
              </p>
            </div>
            <div class="p-3 bg-slate-50 dark:bg-slate-700/30 rounded-xl">
              <p class="text-xs text-slate-500 font-medium">Professor</p>
              <p class="text-sm font-semibold truncate">{{ auth.professor?.name?.split(' ')[0] || '-' }}</p>
            </div>
          </div>
          
          <div v-if="auth.motivo_saida" class="text-xs text-slate-500 flex items-center gap-1.5 p-2 bg-amber-50 dark:bg-amber-500/5 rounded-lg">
            <Icon name="heroicons:chat-bubble-left-20-solid" class="w-3.5 h-3.5 text-amber-500" />
            <span class="truncate">{{ auth.motivo_saida }}</span>
          </div>
        </div>
        
        <!-- Card Footer -->
        <div class="p-4 pt-3 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
          <button @click="openExitModal(auth)" class="w-full px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white rounded-xl transition-all flex items-center justify-center gap-2 text-sm font-semibold shadow-md">
            <Icon name="heroicons:arrow-right-start-on-rectangle-20-solid" class="w-4 h-4" />
            Registrar Saída
          </button>
        </div>
      </div>
    </div>

    <!-- Modal de Registro de Saída Premium -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div v-if="showExitModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="showExitModal = false">
          <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
          <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-lg w-full max-h-[85vh] overflow-y-auto animate-slideUp">
            <div v-if="selectedAuth">
              <!-- Header -->
              <div class="sticky top-0 z-10 bg-gradient-to-r from-emerald-600 to-emerald-700 p-6 text-white rounded-t-2xl">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                      <Icon name="heroicons:arrow-right-start-on-rectangle-20-solid" class="w-6 h-6" />
                    </div>
                    <div>
                      <h2 class="text-2xl font-bold">Registrar Saída</h2>
                      <p class="text-emerald-100 text-sm mt-0.5">Confirme a saída do aluno</p>
                    </div>
                  </div>
                  <button @click="showExitModal = false" class="w-9 h-9 rounded-full bg-white/20 hover:bg-white/30 transition-colors flex items-center justify-center">
                    <Icon name="heroicons:x-mark-20-solid" class="w-5 h-5" />
                  </button>
                </div>
              </div>

              <!-- Conteúdo -->
              <div class="p-6 space-y-5">
                <!-- Card do Aluno -->
                <div class="relative overflow-hidden bg-gradient-to-r from-emerald-50 to-emerald-100/30 dark:from-emerald-500/10 dark:to-transparent rounded-xl p-5">
                  <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-md">
                      <Icon name="heroicons:user-20-solid" class="w-7 h-7 text-white" />
                    </div>
                    <div>
                      <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">Aluno</p>
                      <p class="font-bold text-xl text-slate-900 dark:text-white">{{ selectedAuth.aluno_nome }}</p>
                    </div>
                  </div>
                  
                  <div class="grid grid-cols-2 gap-3 mt-4 pt-3 border-t border-emerald-200 dark:border-emerald-500/20">
                    <div>
                      <p class="text-xs text-slate-500 font-medium">Turma</p>
                      <p class="font-semibold text-slate-900 dark:text-white">{{ selectedAuth.turma }}</p>
                    </div>
                    <div>
                      <p class="text-xs text-slate-500 font-medium">Horário Autorizado</p>
                      <p class="font-bold font-mono text-emerald-600 dark:text-emerald-400">{{ formatTime(selectedAuth.horario_saida) }}</p>
                    </div>
                    <div>
                      <p class="text-xs text-slate-500 font-medium">Aula</p>
                      <p class="font-semibold">{{ selectedAuth.aula_numero }}ª Aula</p>
                    </div>
                    <div>
                      <p class="text-xs text-slate-500 font-medium">Professor</p>
                      <p class="font-semibold">{{ selectedAuth.professor?.name?.split(' ')[0] || '-' }}</p>
                    </div>
                  </div>
                </div>

                <div v-if="selectedAuth.motivo_saida" class="p-4 bg-amber-50 dark:bg-amber-500/10 rounded-xl border border-amber-200 dark:border-amber-500/20">
                  <p class="text-xs font-medium text-amber-700 dark:text-amber-400 flex items-center gap-1.5">
                    <Icon name="heroicons:chat-bubble-left-20-solid" class="w-4 h-4" />
                    Motivo da Saída
                  </p>
                  <p class="text-sm text-slate-700 dark:text-slate-300 mt-1.5">{{ selectedAuth.motivo_saida }}</p>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Observações <span class="text-slate-400 text-xs font-normal">(opcional)</span></label>
                  <textarea 
                    v-model="exitForm.observacoes" 
                    rows="3" 
                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all resize-none"
                    placeholder="Registre alguma observação sobre esta saída..."
                  ></textarea>
                </div>

                <div class="p-4 bg-amber-50 dark:bg-amber-500/10 rounded-xl border border-amber-200 dark:border-amber-500/20">
                  <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-amber-200 dark:bg-amber-500/30 flex items-center justify-center flex-shrink-0">
                      <Icon name="heroicons:information-circle-20-solid" class="w-4 h-4 text-amber-700 dark:text-amber-400" />
                    </div>
                    <div>
                      <p class="text-sm font-semibold text-amber-800 dark:text-amber-400">Notificação Automática</p>
                      <p class="text-xs text-amber-700 dark:text-amber-300 mt-1">
                        O responsável será notificado por e-mail e WhatsApp assim que a saída for confirmada.
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Footer -->
              <div class="border-t border-slate-100 dark:border-slate-700 p-5 bg-slate-50 dark:bg-slate-800/30 rounded-b-2xl flex justify-end gap-3">
                <button @click="showExitModal = false" class="flex-1 px-4 py-3 rounded-xl border-2 border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all font-semibold">
                  Cancelar
                </button>
                <button @click="confirmExit" :disabled="submitting" class="flex-1 px-4 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white transition-all flex items-center justify-center gap-2 font-semibold shadow-md disabled:opacity-50">
                  <Icon v-if="submitting" name="heroicons:arrow-path-20-solid" class="w-4 h-4 animate-spin" />
                  <Icon v-else name="heroicons:check-20-solid" class="w-4 h-4" />
                  {{ submitting ? 'Registrando...' : 'Confirmar Saída' }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- Toast Notifications -->
    <div v-if="toastMessage" class="fixed bottom-6 right-6 z-50 animate-slideUp">
      <div :class="[
        'px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 min-w-[280px]',
        toastVariant === 'success' ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white' : 'bg-gradient-to-r from-red-500 to-red-600 text-white'
      ]">
        <Icon :name="toastVariant === 'success' ? 'heroicons:check-circle-20-solid' : 'heroicons:x-circle-20-solid'" class="w-6 h-6" />
        <span class="font-medium">{{ toastMessage }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useGateway } from '~/composables/useGateway'

definePageMeta({
  middleware: 'auth',
  layout: 'gateway'
})

const { pendingExits, loading, fetchPendingExits, search, registerExit } = useGateway()

const searchTerm = ref('')
const showExitModal = ref(false)
const selectedAuth = ref(null)
const submitting = ref(false)
const exitForm = ref({ observacoes: '' })
const toastMessage = ref('')
const toastVariant = ref('success')
const currentTime = ref('')
const currentDate = ref('')
let timeInterval = null

const showToast = (message, variant = 'success') => {
  toastMessage.value = message
  toastVariant.value = variant
  setTimeout(() => {
    toastMessage.value = ''
  }, 3000)
}

const updateDateTime = () => {
  const now = new Date()
  currentTime.value = now.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit', second: '2-digit' })
  currentDate.value = now.toLocaleDateString('pt-BR', { day: '2-digit', month: 'long', year: 'numeric' })
}

const loadData = async () => {
  await fetchPendingExits()
}

const handleSearch = async () => {
  if (!searchTerm.value.trim()) {
    await loadData()
    return
  }
  
  loading.value = true
  try {
    const results = await search(searchTerm.value)
    pendingExits.value = results
  } catch (error) {
    console.error('Erro ao buscar:', error)
    showToast('Erro ao buscar dados', 'error')
  } finally {
    loading.value = false
  }
}

const clearSearch = async () => {
  searchTerm.value = ''
  await loadData()
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
    showToast('✅ Saída registrada com sucesso! Responsável foi notificado.', 'success')
    showExitModal.value = false
    await loadData()
  } catch (error) {
    const message = error.response?.data?.message || 'Erro ao registrar saída'
    showToast(message, 'error')
  } finally {
    submitting.value = false
  }
}

const isLate = (auth) => {
  if (!auth.horario_saida) return false
  const [hour, minute] = auth.horario_saida.split(':')
  const authTime = new Date()
  authTime.setHours(parseInt(hour), parseInt(minute), 0)
  const now = new Date()
  return now > authTime
}

const formatTime = (time) => time?.substring(0, 5) || '—'

onMounted(() => {
  updateDateTime()
  timeInterval = setInterval(updateDateTime, 1000)
  loadData()
})

onUnmounted(() => {
  if (timeInterval) clearInterval(timeInterval)
})
</script>

<style scoped>
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fadeIn {
  animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

.animate-slideUp {
  animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.2s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}
</style>