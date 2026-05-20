<template>
  <div>
    <div class="mb-8">
      <div class="flex items-center gap-3 mb-2">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-500 to-slate-600 flex items-center justify-center shadow-lg shadow-slate-500/20">
          <Icon name="heroicons:clock-20-solid" class="w-6 h-6 text-white" />
        </div>
        <div>
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Autorizações Pendentes</h1>
          <p class="text-slate-500 dark:text-slate-400">Valide ou rejeite as solicitações de saída dos alunos</p>
        </div>
      </div>
    </div>

    <!-- Cards de Estatísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Pendentes</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ pendingAuthorizations.length }}</p>
          </div>
          <div class="w-10 h-10 rounded-xl bg-yellow-100 dark:bg-yellow-500/20 flex items-center justify-center">
            <Icon name="heroicons:clock-20-solid" class="w-5 h-5 text-yellow-600 dark:text-yellow-400" />
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Aprovadas Hoje</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ stats.aprovadas_hoje || 0 }}</p>
          </div>
          <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-500/20 flex items-center justify-center">
            <Icon name="heroicons:check-badge-20-solid" class="w-5 h-5 text-green-600 dark:text-green-400" />
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Total no Mês</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ stats.total_mes || 0 }}</p>
          </div>
          <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center">
            <Icon name="heroicons:document-text-20-solid" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
          </div>
        </div>
      </div>
    </div>

    <!-- Lista de Autorizações -->
    <div v-if="loading" class="flex justify-center py-12">
      <Icon name="heroicons:arrow-path-20-solid" class="w-8 h-8 animate-spin text-slate-400" />
    </div>

    <div v-else-if="pendingAuthorizations.length === 0" class="text-center py-16 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700">
      <Icon name="heroicons:check-circle-20-solid" class="w-20 h-20 mx-auto mb-4 text-emerald-500 opacity-50" />
      <h3 class="text-xl font-semibold text-slate-700 dark:text-slate-300">Nenhuma autorização pendente</h3>
      <p class="text-slate-500 mt-2">Todas as solicitações foram processadas</p>
      <NuxtLink to="/professor/dashboard" class="text-slate-500 hover:text-slate-700 text-sm mt-4 inline-block">
        Voltar ao Dashboard →
      </NuxtLink>
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <AppCard v-for="auth in pendingAuthorizations" :key="auth.id" class="hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
        <template #header>
          <div class="flex justify-between items-start">
            <div>
              <div class="flex items-center gap-2 mb-1">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ auth.aluno_nome }}</h3>
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-400">
                  Pendente
                </span>
              </div>
              <p class="text-sm text-slate-500">{{ auth.turma }}</p>
            </div>
            <div class="text-right">
              <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ formatTime(auth.horario_saida) }}</p>
              <p class="text-xs text-slate-500">{{ auth.aula_numero }}ª Aula</p>
            </div>
          </div>
        </template>

        <div class="space-y-4">
          <div v-if="auth.motivo_saida" class="flex items-start gap-2 text-sm p-3 bg-slate-50 dark:bg-slate-800/50 rounded-lg">
            <Icon name="heroicons:chat-bubble-left-20-solid" class="w-4 h-4 text-slate-400 mt-0.5 flex-shrink-0" />
            <span class="text-slate-600 dark:text-slate-400">{{ auth.motivo_saida }}</span>
          </div>
          
          <div class="flex items-center gap-2 text-xs text-slate-400">
            <Icon name="heroicons:calendar-20-solid" class="w-4 h-4" />
            <span>Solicitado em {{ formatDate(auth.created_at) }}</span>
            <Icon name="heroicons:user-20-solid" class="w-4 h-4 ml-2" />
            <span>Por: {{ auth.admin?.name || 'Coordenação' }}</span>
          </div>

          <div class="pt-4 flex gap-3">
            <button 
              @click="openApproveModal(auth)"
              class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-all duration-200 hover:shadow-lg"
            >
              <Icon name="heroicons:check-20-solid" class="w-5 h-5" />
              Aprovar
            </button>

            <button 
              @click="openRejectModal(auth)"
              class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-all duration-200 hover:shadow-lg"
            >
              <Icon name="heroicons:x-mark-20-solid" class="w-5 h-5" />
              Rejeitar
            </button>
          </div>
        </div>
      </AppCard>
    </div>

    <!-- Modal de Aprovação -->
    <AppModal v-model="showApproveModal" title="Aprovar Autorização" size="md">
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
          <textarea v-model="approveForm.observacao" rows="3" class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 transition-all" placeholder="Adicione uma observação..."></textarea>
        </div>

        <div class="flex gap-3 pt-4">
          <button @click="showApproveModal = false" class="flex-1 px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            Cancelar
          </button>
          <button @click="confirmApprove" :disabled="submitting" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors disabled:opacity-50">
            <Icon v-if="submitting" name="heroicons:arrow-path-20-solid" class="w-4 h-4 animate-spin inline mr-2" />
            Confirmar Aprovação
          </button>
        </div>
      </div>
    </AppModal>

    <!-- Modal de Rejeição -->
    <AppModal v-model="showRejectModal" title="Rejeitar Autorização" size="md">
      <div v-if="selectedAuth" class="space-y-4">
        <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-xl">
          <p class="text-sm text-slate-500">Aluno</p>
          <p class="font-semibold text-slate-900 dark:text-white">{{ selectedAuth.aluno_nome }}</p>
          <p class="text-sm text-slate-500 mt-2">Turma</p>
          <p class="font-semibold text-slate-900 dark:text-white">{{ selectedAuth.turma }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Motivo da Rejeição *</label>
          <textarea v-model="rejectForm.observacao" rows="4" class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-red-500 transition-all" placeholder="Informe o motivo da rejeição..."></textarea>
        </div>

        <div class="flex gap-3 pt-4">
          <button @click="showRejectModal = false" class="flex-1 px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            Cancelar
          </button>
          <button @click="confirmReject" :disabled="submitting" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors disabled:opacity-50">
            <Icon v-if="submitting" name="heroicons:arrow-path-20-solid" class="w-4 h-4 animate-spin inline mr-2" />
            Confirmar Rejeição
          </button>
        </div>
      </div>
    </AppModal>

    <AppToast v-if="toastMessage" :message="toastMessage" :variant="toastVariant" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthorizations } from '~/composables/useAuthorizations'
import { useToast } from '~/composables/useToast'
import AppCard from '~/components/common/AppCard.vue'
import AppModal from '~/components/common/AppModal.vue'

definePageMeta({
  middleware: 'auth',
  layout: 'professor'
})

const { pendingAuthorizations, loading, fetchPending, approve, reject, getStats } = useAuthorizations()
const { show: toast } = useToast()

const stats = ref({
  aprovadas_hoje: 0,
  total_mes: 0
})

const showApproveModal = ref(false)
const showRejectModal = ref(false)
const selectedAuth = ref(null)
const submitting = ref(false)

const approveForm = ref({ com_falta: false, observacao: '' })
const rejectForm = ref({ observacao: '' })

const loadData = async () => {
  await fetchPending()
  const statsData = await getStats()
  stats.value = statsData
}

const openApproveModal = (auth) => {
  selectedAuth.value = auth
  approveForm.value = { com_falta: false, observacao: '' }
  showApproveModal.value = true
}

const openRejectModal = (auth) => {
  selectedAuth.value = auth
  rejectForm.value = { observacao: '' }
  showRejectModal.value = true
}

const confirmApprove = async () => {
  if (!selectedAuth.value) return
  
  submitting.value = true
  try {
    await approve(selectedAuth.value.id, approveForm.value.com_falta, approveForm.value.observacao)
    toast('✅ Autorização aprovada com sucesso!', 'success')
    showApproveModal.value = false
    await loadData()
  } catch (error) {
    const message = error.response?.data?.message || 'Erro ao aprovar autorização'
    toast(message, 'error')
  } finally {
    submitting.value = false
  }
}

const confirmReject = async () => {
  if (!selectedAuth.value) return
  if (!rejectForm.value.observacao) {
    toast('Informe o motivo da rejeição', 'error')
    return
  }
  
  submitting.value = true
  try {
    await reject(selectedAuth.value.id, rejectForm.value.observacao)
    toast('Autorização rejeitada', 'warning')
    showRejectModal.value = false
    await loadData()
  } catch (error) {
    const message = error.response?.data?.message || 'Erro ao rejeitar autorização'
    toast(message, 'error')
  } finally {
    submitting.value = false
  }
}

const formatTime = (time) => {
  if (!time) return '—'
  return time.substring(0, 5)
}

const formatDate = (date) => {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('pt-BR', { 
    day: '2-digit', 
    month: '2-digit', 
    hour: '2-digit', 
    minute: '2-digit' 
  })
}

onMounted(() => {
  loadData()
})
</script>