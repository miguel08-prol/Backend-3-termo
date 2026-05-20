<template>
  <div>
    <div class="mb-8">
      <div class="flex items-center gap-3 mb-2">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-500 to-slate-600 flex items-center justify-center shadow-lg shadow-slate-500/20">
          <Icon name="heroicons:document-text-20-solid" class="w-6 h-6 text-white" />
        </div>
        <div>
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Histórico de Autorizações</h1>
          <p class="text-slate-500 dark:text-slate-400">Todas as autorizações que você já validou</p>
        </div>
      </div>
    </div>

    <!-- Filtros -->
    <AppCard class="mb-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status</label>
          <select v-model="filters.status" class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl">
            <option value="">Todos</option>
            <option value="approved_by_professor">✅ Aprovados</option>
            <option value="rejected">❌ Rejeitados</option>
            <option value="completed">🏁 Concluídos</option>
          </select>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Buscar Aluno</label>
          <input v-model="filters.search" type="text" placeholder="Nome do aluno..." class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl" @keyup.enter="applyFilters">
        </div>
        
        <div class="flex items-end">
          <button @click="applyFilters" class="w-full px-4 py-2.5 bg-slate-600 hover:bg-slate-700 text-white rounded-xl transition-colors">
            Filtrar
          </button>
        </div>
      </div>
    </AppCard>

    <!-- Tabela -->
    <AppCard no-padding>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-slate-50 dark:bg-slate-800/50 border-b-2 border-slate-200 dark:border-slate-700">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Aluno</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Turma</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Data/Horário</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Falta</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Validação</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            <tr v-for="auth in authorizations" :key="auth.id" class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
              <td class="px-6 py-4">
                <div class="font-medium text-slate-900 dark:text-white">{{ auth.aluno_nome }}</div>
                <div class="text-xs text-slate-400">{{ auth.motivo_saida || '-' }}</div>
              </td>
              <td class="px-6 py-4">
                <span class="px-2 py-1 text-xs font-mono bg-slate-100 dark:bg-slate-700 rounded-lg">{{ auth.turma }}</span>
              </td>
              <td class="px-6 py-4">
                <div class="font-mono text-sm">{{ formatDateTime(auth.horario_saida) }}</div>
                <div class="text-xs text-slate-400">{{ formatDate(auth.created_at) }}</div>
              </td>
              <td class="px-6 py-4">
                <span :class="statusBadge(auth.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                  {{ statusText(auth.status) }}
                </span>
              </td>
              <td class="px-6 py-4">
                <span v-if="auth.com_falta" class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400">
                  Com Falta
                </span>
                <span v-else class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400">
                  Sem Falta
                </span>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm">{{ formatDateTime(auth.autorizado_em) || '-' }}</div>
                <div v-if="auth.validation?.observacao" class="text-xs text-slate-400 mt-1 truncate max-w-[200px]" :title="auth.validation.observacao">
                  Obs: {{ auth.validation.observacao }}
                </div>
              </td>
            </tr>
            
            <tr v-if="!loading && authorizations.length === 0">
              <td colspan="6" class="px-6 py-12 text-center">
                <Icon name="heroicons:document-text-20-solid" class="w-16 h-16 mx-auto mb-3 text-slate-300 dark:text-slate-600" />
                <p class="text-slate-500 dark:text-slate-400">Nenhuma autorização encontrada</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Paginação -->
      <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-4">
        <p class="text-sm text-slate-500">
          Mostrando <span class="font-semibold">{{ authorizations.length }}</span> de <span class="font-semibold">{{ pagination.total }}</span> registros
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
import { useAuthorizations } from '~/composables/useAuthorizations'
import AppCard from '~/components/common/AppCard.vue'

definePageMeta({
  middleware: 'auth',
  layout: 'professor'
})

const { authorizations, loading, pagination, fetchHistory } = useAuthorizations()

const filters = ref({
  status: '',
  search: ''
})

const loadData = async (page = 1) => {
  await fetchHistory(page)
}

const applyFilters = () => {
  // Implementar filtros no backend
  loadData()
}

const prevPage = () => {
  if (pagination.value.current_page > 1) {
    loadData(pagination.value.current_page - 1)
  }
}

const nextPage = () => {
  if (pagination.value.current_page < pagination.value.last_page) {
    loadData(pagination.value.current_page + 1)
  }
}

const statusBadge = (status) => {
  const badges = {
    approved_by_professor: 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400',
    rejected: 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400',
    completed: 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400'
  }
  return badges[status] || 'bg-gray-100 text-gray-700'
}

const statusText = (status) => {
  const texts = {
    approved_by_professor: 'Aprovado',
    rejected: 'Rejeitado',
    completed: 'Concluído'
  }
  return texts[status] || status
}

const formatDateTime = (dateTime) => {
  if (!dateTime) return '—'
  const date = new Date(dateTime)
  if (isNaN(date.getTime())) return dateTime
  return date.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' })
}

const formatDate = (date) => {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

onMounted(() => {
  loadData()
})
</script>