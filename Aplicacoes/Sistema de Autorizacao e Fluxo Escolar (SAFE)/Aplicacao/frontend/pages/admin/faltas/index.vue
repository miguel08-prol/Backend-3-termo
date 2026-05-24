<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-md">
            <Icon name="heroicons:exclamation-triangle-20-solid" class="w-6 h-6 text-white" />
          </div>
          <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Relatório de Faltas</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Gerencie e acompanhe faltas dos alunos</p>
          </div>
        </div>
      </div>
      
      <button @click="exportData" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition-colors flex items-center gap-2">
        <Icon name="heroicons:document-arrow-down-20-solid" class="w-5 h-5" />
        Exportar Relatório
      </button>
    </div>
    
    <!-- Filtros -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Turma</label>
          <select v-model="filters.turma_id" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border rounded-lg text-sm">
            <option value="">Todas as turmas</option>
            <option v-for="turma in turmas" :key="turma.id" :value="turma.id">{{ turma.nome }}</option>
          </select>
        </div>
        
        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Mês</label>
          <select v-model="filters.mes" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border rounded-lg text-sm">
            <option value="">Todos</option>
            <option v-for="(nome, index) in meses" :key="index" :value="index + 1">{{ nome }}</option>
          </select>
        </div>
        
        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Ano</label>
          <select v-model="filters.ano" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border rounded-lg text-sm">
            <option v-for="ano in anos" :key="ano" :value="ano">{{ ano }}</option>
          </select>
        </div>
        
        <div class="flex items-end">
          <button @click="loadData" class="w-full px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition-colors flex items-center justify-center gap-2">
            <Icon name="heroicons:magnifying-glass-20-solid" class="w-4 h-4" />
            Filtrar
          </button>
        </div>
      </div>
    </div>
    
    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="bg-white dark:bg-slate-900 rounded-xl p-4 border border-slate-200 dark:border-slate-800">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-500/20 flex items-center justify-center">
            <Icon name="heroicons:users-20-solid" class="w-5 h-5 text-red-600" />
          </div>
          <div>
            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ stats.total_alunos }}</p>
            <p class="text-xs text-slate-500">Total de Alunos</p>
          </div>
        </div>
      </div>
      
      <div class="bg-white dark:bg-slate-900 rounded-xl p-4 border border-slate-200 dark:border-slate-800">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center">
            <Icon name="heroicons:exclamation-triangle-20-solid" class="w-5 h-5 text-amber-600" />
          </div>
          <div>
            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ stats.total_faltas }}</p>
            <p class="text-xs text-slate-500">Total de Faltas</p>
          </div>
        </div>
      </div>
      
      <div class="bg-white dark:bg-slate-900 rounded-xl p-4 border border-slate-200 dark:border-slate-800">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-500/20 flex items-center justify-center">
            <Icon name="heroicons:chart-bar-20-solid" class="w-5 h-5 text-green-600" />
          </div>
          <div>
            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ stats.media_faltas }}</p>
            <p class="text-xs text-slate-500">Média de Faltas por Aluno</p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Tabela de Alunos com Faltas -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-slate-50 dark:bg-slate-800/50 border-b">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Aluno</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Turma</th>
              <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Total Faltas</th>
              <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Faltas no Mês</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Última Falta</th>
              <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Ações</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <tr v-for="aluno in alunos" :key="aluno.id" class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
              <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center">
                    <Icon name="heroicons:user-20-solid" class="w-4 h-4 text-blue-600" />
                  </div>
                  <span class="font-medium">{{ aluno.name }}</span>
                </div>
              </td>
              <td class="px-4 py-3">{{ aluno.turma || '—' }}</td>
              <td class="px-4 py-3 text-center">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 dark:bg-red-500/20 text-red-600 font-bold">
                  {{ aluno.total_faltas || 0 }}
                </span>
              </td>
              <td class="px-4 py-3 text-center">{{ aluno.faltas_mes || 0 }}</td>
              <td class="px-4 py-3">{{ formatDate(aluno.ultima_falta) }}</td>
              <td class="px-4 py-3 text-center">
                <button @click="openAlunoModal(aluno)" class="p-1.5 rounded-lg text-slate-400 hover:text-amber-500 transition-colors">
                  <Icon name="heroicons:eye-20-solid" class="w-4 h-4" />
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    
    <!-- Modal de Detalhes do Aluno -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div v-if="showAlunoModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="closeAlunoModal">
          <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
          <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 bg-gradient-to-r from-amber-500 to-amber-600 p-4 text-white rounded-t-2xl">
              <div class="flex items-center justify-between">
                <div>
                  <h2 class="text-xl font-bold">{{ selectedAluno?.name }}</h2>
                  <p class="text-amber-100 text-sm">{{ selectedAluno?.turma }}</p>
                </div>
                <button @click="closeAlunoModal" class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center">
                  <Icon name="heroicons:x-mark-20-solid" class="w-5 h-5" />
                </button>
              </div>
            </div>
            
            <div class="p-4">
              <FaltasTable :aluno-id="selectedAluno?.id" :periodo="periodo" />
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useApi } from '~/composables/useApi'
import { useToast } from '~/composables/useToast'
import FaltasTable from '~/components/student/FaltasTable.vue'

definePageMeta({ layout: 'admin' })

const { get } = useApi()
const { show: toast } = useToast()

const alunos = ref([])
const turmas = ref([])
const loading = ref(false)
const stats = reactive({ total_alunos: 0, total_faltas: 0, media_faltas: 0 })
const showAlunoModal = ref(false)
const selectedAluno = ref(null)

const filters = reactive({
  turma_id: '',
  mes: new Date().getMonth() + 1,
  ano: new Date().getFullYear()
})

const meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro']
const anos = [2023, 2024, 2025, 2026]

const periodo = computed(() => ({
  mes: filters.mes,
  ano: filters.ano
}))

const formatDate = (date) => {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('pt-BR')
}

const loadTurmas = async () => {
  try {
    const response = await get('/turmas/list')
    turmas.value = response
  } catch (error) {
    console.error('Erro ao carregar turmas:', error)
  }
}

const loadData = async () => {
  loading.value = true
  try {
    const params = {}
    if (filters.turma_id) params.turma_id = filters.turma_id
    if (filters.mes) params.mes = filters.mes
    if (filters.ano) params.ano = filters.ano
    
    const response = await get('/faltas/relatorio', params)
    alunos.value = response.alunos || []
    stats.total_alunos = response.total_alunos || 0
    stats.total_faltas = response.total_faltas || 0
    stats.media_faltas = response.media_faltas || 0
  } catch (error) {
    console.error('Erro ao carregar dados:', error)
    toast('Erro ao carregar relatório', 'error')
  } finally {
    loading.value = false
  }
}

const openAlunoModal = (aluno) => {
  selectedAluno.value = aluno
  showAlunoModal.value = true
}

const closeAlunoModal = () => {
  showAlunoModal.value = false
  selectedAluno.value = null
}

const exportData = () => {
  toast('Funcionalidade em desenvolvimento', 'info')
}

onMounted(() => {
  loadTurmas()
  loadData()
})
</script>