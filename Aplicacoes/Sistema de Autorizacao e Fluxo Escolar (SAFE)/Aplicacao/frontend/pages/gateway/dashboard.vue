<template>
  <div class="space-y-8 animate-fadeIn">
    <!-- Hero Section Premium -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-600 via-emerald-700 to-teal-800 p-8 text-white shadow-2xl">
      <!-- Elementos decorativos -->
      <div class="absolute top-0 right-0 w-80 h-80 bg-white/5 rounded-full -mr-40 -mt-40 blur-2xl"></div>
      <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full -ml-32 -mb-32 blur-2xl"></div>
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-emerald-400/5 rounded-full blur-3xl"></div>
      
      <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div class="flex items-center gap-5">
          <div class="w-20 h-20 rounded-2xl bg-white/15 backdrop-blur-md flex items-center justify-center shadow-xl ring-1 ring-white/20">
            <Icon name="heroicons:building-library-20-solid" class="w-10 h-10" />
          </div>
          <div>
            <h1 class="text-4xl font-black tracking-tight">Portaria SAFE</h1>
            <div class="flex items-center gap-3 mt-2">
              <div class="flex items-center gap-1.5">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                </span>
                <span class="text-emerald-100 text-sm font-medium">Sistema Online</span>
              </div>
              <div class="w-px h-4 bg-white/30"></div>
              <span class="text-emerald-100/80 text-sm">Autorização de Saída Escolar</span>
            </div>
          </div>
        </div>
        
        <div class="flex items-center gap-4">
          <div class="bg-white/10 backdrop-blur-md rounded-2xl px-6 py-3 text-center ring-1 ring-white/20">
            <p class="text-3xl font-bold font-mono tracking-wider">{{ currentTime }}</p>
            <p class="text-xs font-medium text-emerald-200 mt-1">{{ currentDate }}</p>
          </div>
          <button @click="simulateScan" class="group px-6 py-3 bg-white/10 backdrop-blur-md hover:bg-white/20 rounded-2xl transition-all duration-300 flex items-center gap-2 font-semibold ring-1 ring-white/20 hover:ring-white/40">
            <Icon name="heroicons:qr-code-20-solid" class="w-5 h-5 group-hover:scale-110 transition-transform" />
            <span>Scanner Rápido</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Stats Cards Premium -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div 
        v-for="(stat, index) in statCards" 
        :key="stat.key"
        class="group relative bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1.5 cursor-pointer overflow-hidden"
        :style="{ animationDelay: `${index * 0.05}s` }"
        @click="stat.action && navigateTo(stat.action)"
      >
        <!-- Fundo decorativo -->
        <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full bg-gradient-to-br from-emerald-500/5 to-transparent"></div>
        
        <div class="relative z-10">
          <div class="flex items-start justify-between mb-3">
            <div>
              <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ stat.label }}</p>
              <p class="text-4xl font-black text-slate-900 dark:text-white mt-2">{{ stats[stat.key] || 0 }}</p>
              <p v-if="stat.subtext" class="text-xs text-slate-400 mt-1.5">{{ stat.subtext }}</p>
            </div>
            <div :class="[
              'w-14 h-14 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110 group-hover:rotate-6',
              stat.bgColor
            ]">
              <Icon :name="stat.icon" class="w-7 h-7" :class="stat.iconColor" />
            </div>
          </div>
          <div class="mt-4 h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-emerald-400 to-emerald-500 rounded-full transition-all duration-700" :style="{ width: `${getProgressWidth(stat.key)}%` }"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Seção Principal com Grid 2 colunas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Próximas Saídas -->
      <div class="bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
        <div class="p-5 border-b border-slate-100 dark:border-slate-700 bg-gradient-to-r from-slate-50 to-white dark:from-slate-800/50 dark:to-slate-800">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center">
                  <Icon name="heroicons:clock-20-solid" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                </div>
                <div>
                  <h2 class="text-xl font-bold text-slate-800 dark:text-white">Próximas Saídas</h2>
                  <p class="text-xs text-slate-400 mt-0.5">Autorizações aprovadas aguardando registro</p>
                </div>
              </div>
            </div>
            <div class="flex gap-2">
              <div class="relative">
                <input 
                  v-model="searchTerm" 
                  type="text" 
                  placeholder="Buscar aluno ou turma..." 
                  class="pl-10 pr-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 w-56 transition-all"
                  @keyup.enter="searchAndFilter"
                />
                <Icon name="heroicons:magnifying-glass-20-solid" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
              </div>
              <NuxtLink to="/gateway/exits" class="px-4 py-2.5 text-sm bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 rounded-xl hover:bg-emerald-200 dark:hover:bg-emerald-500/30 transition-all flex items-center gap-1.5 font-medium">
                Ver todas
                <Icon name="heroicons:arrow-right-20-solid" class="w-4 h-4" />
              </NuxtLink>
            </div>
          </div>
        </div>

        <div class="p-4">
          <div v-if="loading" class="flex justify-center py-12">
            <div class="flex flex-col items-center gap-3">
              <Icon name="heroicons:arrow-path-20-solid" class="w-8 h-8 animate-spin text-emerald-500" />
              <p class="text-slate-500 text-sm">Carregando saídas pendentes...</p>
            </div>
          </div>

          <div v-else-if="pendingExits.length === 0" class="text-center py-12">
            <div class="w-20 h-20 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center mx-auto mb-4">
              <Icon name="heroicons:check-circle-20-solid" class="w-10 h-10 text-emerald-500" />
            </div>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Nenhuma saída pendente</p>
            <p class="text-xs text-slate-400 mt-1">Todas as autorizações já foram processadas</p>
          </div>

          <div v-else class="space-y-3 max-h-[500px] overflow-y-auto pr-1 custom-scrollbar">
            <div 
              v-for="auth in pendingExits.slice(0, 6)" 
              :key="auth.id" 
              class="group relative bg-gradient-to-r from-slate-50 to-white dark:from-slate-800/50 dark:to-slate-800 rounded-xl hover:shadow-lg transition-all duration-300 border border-slate-100 dark:border-slate-700 overflow-hidden"
            >
              <!-- Indicador de horário -->
              <div :class="[
                'absolute left-0 top-0 bottom-0 w-1',
                isLate(auth) ? 'bg-red-500' : 'bg-emerald-500'
              ]"></div>
              
              <div class="p-4 pl-5">
                <div class="flex items-center justify-between flex-wrap gap-3">
                  <div class="flex items-center gap-3 flex-1 min-w-0">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-500/20 dark:to-emerald-600/20 flex items-center justify-center flex-shrink-0">
                      <Icon name="heroicons:user-20-solid" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center gap-2 flex-wrap">
                        <p class="font-bold text-slate-900 dark:text-white truncate">{{ auth.aluno_nome }}</p>
                        <span :class="[
                          'px-2 py-0.5 text-[10px] font-bold rounded-full',
                          isLate(auth) ? 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400'
                        ]">
                          {{ isLate(auth) ? '⚠️ Atrasado' : '✅ No horário' }}
                        </span>
                      </div>
                      <div class="flex items-center gap-3 mt-1.5 text-xs text-slate-500 flex-wrap">
                        <span class="flex items-center gap-1">
                          <Icon name="heroicons:building-library-20-solid" class="w-3 h-3" />
                          {{ auth.turma }}
                        </span>
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
                  </div>
                  <div class="text-right flex-shrink-0">
                    <p class="text-2xl font-bold font-mono" :class="isLate(auth) ? 'text-red-500' : 'text-emerald-600 dark:text-emerald-400'">
                      {{ formatTime(auth.horario_saida) }}
                    </p>
                    <p class="text-xs text-slate-400 mb-2">{{ formatRelativeTime(auth.horario_saida) }}</p>
                    <button 
                      @click="openExitModal(auth)"
                      class="px-4 py-1.5 text-sm bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-all flex items-center gap-1.5 shadow-sm font-medium"
                    >
                      <Icon name="heroicons:arrow-right-start-on-rectangle-20-solid" class="w-4 h-4" />
                      Registrar Saída
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Atividade Recente -->
      <div class="bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
        <div class="p-5 border-b border-slate-100 dark:border-slate-700 bg-gradient-to-r from-slate-50 to-white dark:from-slate-800/50 dark:to-slate-800">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center">
                  <Icon name="heroicons:document-text-20-solid" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                </div>
                <div>
                  <h2 class="text-xl font-bold text-slate-800 dark:text-white">Últimas Saídas</h2>
                  <p class="text-xs text-slate-400 mt-0.5">Atividade recente da portaria</p>
                </div>
              </div>
            </div>
            <div class="flex gap-2">
              <button 
                @click="exportToCSV" 
                class="px-4 py-2.5 text-sm bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl transition-all flex items-center gap-2 shadow-sm font-medium"
              >
                <Icon name="heroicons:document-arrow-down-20-solid" class="w-4 h-4" />
                Exportar CSV
              </button>
              <NuxtLink to="/gateway/history" class="px-4 py-2.5 text-sm border border-slate-300 dark:border-slate-600 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 transition-all flex items-center gap-1 font-medium">
                Ver todos
                <Icon name="heroicons:arrow-right-20-solid" class="w-4 h-4" />
              </NuxtLink>
            </div>
          </div>
        </div>

        <div class="p-4">
          <div v-if="loadingHistory" class="text-center py-12">
            <Icon name="heroicons:arrow-path-20-solid" class="w-8 h-8 animate-spin mx-auto text-slate-400" />
            <p class="text-slate-500 text-sm mt-3">Carregando histórico...</p>
          </div>

          <div v-else-if="recentHistory.length === 0" class="text-center py-12">
            <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center mx-auto mb-3">
              <Icon name="heroicons:inbox-20-solid" class="w-8 h-8 text-slate-400" />
            </div>
            <p class="text-slate-500 font-medium">Nenhuma atividade registrada</p>
            <p class="text-xs text-slate-400 mt-1">As saídas aparecerão aqui quando forem registradas</p>
          </div>

          <div v-else class="space-y-3 max-h-[500px] overflow-y-auto pr-1 custom-scrollbar">
            <div v-for="entry in recentHistory.slice(0, 6)" :key="entry.id" class="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-all duration-200">
              <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-500/20 dark:to-emerald-600/20 flex items-center justify-center flex-shrink-0">
                <Icon name="heroicons:check-20-solid" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
              </div>
              <div class="flex-1 min-w-0">
                <p class="font-semibold text-slate-900 dark:text-white truncate">
                  {{ entry.authorization?.aluno_nome }}
                  <span class="text-xs text-slate-400 ml-2 font-normal">({{ entry.authorization?.turma }})</span>
                </p>
                <div class="flex items-center gap-3 mt-1 text-xs text-slate-400 flex-wrap">
                  <span class="flex items-center gap-1">
                    <Icon name="heroicons:user-20-solid" class="w-3 h-3" />
                    {{ entry.portaria?.name?.split(' ')[0] || 'Portaria' }}
                  </span>
                  <span class="flex items-center gap-1">
                    <Icon name="heroicons:clock-20-solid" class="w-3 h-3" />
                    {{ formatTimeAgo(entry.created_at) }}
                  </span>
                </div>
              </div>
              <div class="text-right flex-shrink-0">
                <p class="text-lg font-mono font-bold text-emerald-600 dark:text-emerald-400">{{ formatTime(entry.horario_saida) }}</p>
                <p v-if="entry.authorization?.com_falta" class="text-xs text-amber-600 dark:text-amber-400 flex items-center gap-1 justify-end mt-1">
                  <Icon name="heroicons:exclamation-triangle-20-solid" class="w-3 h-3" />
                  Com falta
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de Registro de Saída Premium -->
    <AppModal v-model="showExitModal" title="Registrar Saída" size="md">
      <div v-if="selectedAuth" class="space-y-6">
        <!-- Card do Aluno Premium -->
        <div class="relative overflow-hidden bg-gradient-to-r from-emerald-50 to-emerald-100/30 dark:from-emerald-500/10 dark:to-transparent rounded-2xl p-6">
          <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full -mr-16 -mt-16"></div>
          <div class="relative flex items-center gap-5">
            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-xl">
              <Icon name="heroicons:user-20-solid" class="w-10 h-10 text-white" />
            </div>
            <div>
              <h3 class="text-2xl font-bold text-slate-900 dark:text-white">{{ selectedAuth.aluno_nome }}</h3>
              <div class="flex items-center gap-2 mt-2">
                <span class="px-3 py-1 text-xs font-mono font-semibold bg-emerald-200 dark:bg-emerald-500/30 text-emerald-800 dark:text-emerald-300 rounded-lg">{{ selectedAuth.turma }}</span>
                <span class="px-3 py-1 text-xs font-mono font-semibold bg-slate-200 dark:bg-slate-700 rounded-lg">{{ selectedAuth.aula_numero }}ª Aula</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Informações Grid -->
        <div class="grid grid-cols-2 gap-4">
          <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Horário Autorizado</p>
            <p class="text-2xl font-bold font-mono text-emerald-600 dark:text-emerald-400 mt-1">{{ formatTime(selectedAuth.horario_saida) }}</p>
          </div>
          <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Professor Responsável</p>
            <p class="font-semibold text-slate-900 dark:text-white mt-1">{{ selectedAuth.professor?.name || '—' }}</p>
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

        <div class="bg-amber-50 dark:bg-amber-500/10 rounded-xl p-4 border border-amber-200 dark:border-amber-500/20">
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

        <div class="flex gap-3 pt-4">
          <button @click="showExitModal = false" class="flex-1 px-4 py-3 border-2 border-slate-300 dark:border-slate-600 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all font-semibold">
            Cancelar
          </button>
          <button @click="confirmExit" :disabled="submitting" class="flex-1 px-4 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-semibold rounded-xl transition-all disabled:opacity-50 flex items-center justify-center gap-2 shadow-md">
            <Icon v-if="submitting" name="heroicons:arrow-path-20-solid" class="w-4 h-4 animate-spin" />
            <Icon v-else name="heroicons:check-20-solid" class="w-4 h-4" />
            {{ submitting ? 'Registrando...' : 'Confirmar Saída' }}
          </button>
        </div>
      </div>
    </AppModal>

    <!-- Modal de Scanner Premium -->
    <AppModal v-model="showScanModal" title="Leitor de QR Code" size="sm">
      <div class="text-center py-8">
        <div class="relative w-56 h-56 mx-auto mb-6">
          <!-- Efeito de scanning -->
          <div class="absolute inset-0 border-3 border-emerald-500 rounded-2xl animate-pulse"></div>
          <div class="absolute inset-3 border-2 border-emerald-500 rounded-xl"></div>
          <div class="absolute inset-0 flex items-center justify-center">
            <Icon name="heroicons:camera-20-solid" class="w-14 h-14 text-emerald-500 animate-pulse" />
          </div>
          <div class="absolute -inset-2 bg-emerald-500/20 rounded-2xl blur-xl animate-pulse"></div>
          <!-- Linha de scan animada -->
          <div class="absolute inset-x-4 h-0.5 bg-emerald-500 animate-scan"></div>
        </div>
        <p class="text-slate-600 dark:text-slate-300 font-medium">Aponte a câmera para o QR Code do aluno</p>
        <p class="text-xs text-slate-400 mt-2">Simulação: digite o nome do aluno para buscar</p>
        <div class="mt-6 flex gap-2">
          <input 
            v-model="scanCode" 
            type="text" 
            placeholder="Código ou nome do aluno" 
            class="flex-1 px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
            @keyup.enter="processScan"
          />
          <button @click="processScan" class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white rounded-xl transition-all font-semibold shadow-md">
            Buscar
          </button>
        </div>
      </div>
    </AppModal>

    <!-- Toast Notifications Premium -->
    <Transition name="toast">
      <div v-if="toastMessage" class="fixed bottom-6 right-6 z-50">
        <div :class="[
          'px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 min-w-[280px] animate-slideUp',
          toastVariant === 'success' ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white' : 'bg-gradient-to-r from-red-500 to-red-600 text-white'
        ]">
          <Icon :name="toastVariant === 'success' ? 'heroicons:check-circle-20-solid' : 'heroicons:x-circle-20-solid'" class="w-6 h-6" />
          <span class="font-medium">{{ toastMessage }}</span>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useGateway } from '~/composables/useGateway'
import { useApi } from '~/composables/useApi'
import AppCard from '~/components/common/AppCard.vue'
import AppModal from '~/components/common/AppModal.vue'

definePageMeta({
  middleware: 'auth',
  layout: 'gateway'
})

const { get } = useApi()
const { pendingExits, loading, fetchPendingExits, registerExit, search, fetchHistory } = useGateway()

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
const toastMessage = ref('')
const toastVariant = ref('success')
const currentTime = ref('')
const currentDate = ref('')
let timeInterval = null

const statCards = [
  { key: 'pendentes', label: 'SAÍDAS PENDENTES', icon: 'heroicons:clock-20-solid', bgColor: 'bg-yellow-100 dark:bg-yellow-500/20', iconColor: 'text-yellow-600 dark:text-yellow-400', action: '/gateway/exits', subtext: 'Aguardando registro' },
  { key: 'hoje', label: 'SAÍDAS HOJE', icon: 'heroicons:check-badge-20-solid', bgColor: 'bg-green-100 dark:bg-green-500/20', iconColor: 'text-green-600 dark:text-green-400', subtext: 'Registradas hoje' },
  { key: 'total_mes', label: 'TOTAL NO MÊS', icon: 'heroicons:calendar-20-solid', bgColor: 'bg-blue-100 dark:bg-blue-500/20', iconColor: 'text-blue-600 dark:text-blue-400', subtext: 'Saídas realizadas' },
  { key: 'com_falta', label: 'COM FALTA', icon: 'heroicons:exclamation-triangle-20-solid', bgColor: 'bg-red-100 dark:bg-red-500/20', iconColor: 'text-red-600 dark:text-red-400', subtext: 'Alunos com falta' }
]

const getProgressWidth = (key) => {
  const value = stats.value[key] || 0
  if (key === 'pendentes') return Math.min((value / 20) * 100, 100)
  if (key === 'hoje') return Math.min((value / 50) * 100, 100)
  if (key === 'total_mes') return Math.min((value / 200) * 100, 100)
  if (key === 'com_falta') return Math.min((value / 30) * 100, 100)
  return 0
}

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
  currentDate.value = now.toLocaleDateString('pt-BR', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' })
}

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
    const response = await fetchHistory({ per_page: 10 })
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

const searchAndFilter = async () => {
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

const simulateScan = () => {
  scanCode.value = ''
  showScanModal.value = true
}

const processScan = async () => {
  if (!scanCode.value) {
    showToast('Digite o nome do aluno ou código', 'error')
    return
  }
  
  showScanModal.value = false
  const results = await search(scanCode.value)
  if (results && results.length > 0) {
    openExitModal(results[0])
  } else {
    showToast('Nenhum aluno encontrado com este código', 'error')
  }
  scanCode.value = ''
}

const exportToCSV = async () => {
  try {
    const response = await fetchHistory({ per_page: 1000 })
    const data = response.data || response
    
    if (!data || data.length === 0) {
      showToast('Não há dados para exportar', 'error')
      return
    }
    
    const headers = ['ID', 'Aluno', 'Turma', 'Horário Saída', 'Aula', 'Professor', 'Com Falta', 'Registrado Por', 'Data Registro']
    const rows = data.map(entry => [
      entry.id,
      entry.authorization?.aluno_nome || '-',
      entry.authorization?.turma || '-',
      formatTime(entry.horario_saida),
      `${entry.authorization?.aula_numero || '-'}ª`,
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
    showToast('✅ Histórico exportado com sucesso!', 'success')
  } catch (error) {
    console.error('Erro ao exportar:', error)
    showToast('Erro ao exportar dados', 'error')
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
  if (diff < 1440) return `${Math.floor(diff / 60)}h atrás`
  return `${Math.floor(diff / 1440)} dias atrás`
}

onMounted(() => {
  updateDateTime()
  timeInterval = setInterval(updateDateTime, 1000)
  loadUser()
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

@keyframes scan {
  0% {
    top: 0;
  }
  50% {
    top: 100%;
  }
  100% {
    top: 0;
  }
}

.animate-fadeIn {
  animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

.animate-slideUp {
  animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

.animate-scan {
  animation: scan 2s ease-in-out infinite;
}

.group {
  animation: fadeInUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
  opacity: 0;
}

.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

.dark .custom-scrollbar::-webkit-scrollbar-track {
  background: #1e293b;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb {
  background: #475569;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #64748b;
}

.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from,
.toast-leave-to {
  opacity: 0;
  transform: translateX(30px);
}
</style>