<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-md">
            <Icon name="heroicons:users-20-solid" class="w-6 h-6 text-white" />
          </div>
          <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Turmas</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Gerencie todas as turmas da escola</p>
          </div>
        </div>
      </div>
      
      <button @click="openCreateModal" class="px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors flex items-center gap-2 font-medium shadow-sm">
        <Icon name="heroicons:plus-20-solid" class="w-5 h-5" />
        Nova Turma
      </button>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="relative">
          <Icon name="heroicons:magnifying-glass-20-solid" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
          <input 
            v-model="filters.search" 
            type="text" 
            placeholder="Buscar por nome ou código..." 
            class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-2 focus:ring-blue-500"
            @input="applyFilters"
          />
        </div>
        <div>
          <select v-model="filters.status" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" @change="applyFilters">
            <option value="">Todas as turmas</option>
            <option value="ativa">Apenas Ativas</option>
            <option value="inativa">Apenas Inativas</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading && turmas.length === 0" class="flex justify-center py-16">
      <div class="flex flex-col items-center gap-3">
        <Icon name="heroicons:arrow-path-20-solid" class="w-8 h-8 animate-spin text-blue-500" />
        <p class="text-slate-500">Carregando turmas...</p>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else-if="turmas.length === 0" class="text-center py-16 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800">
      <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-3">
        <Icon name="heroicons:users-20-solid" class="w-8 h-8 text-slate-300 dark:text-slate-600" />
      </div>
      <p class="text-slate-500">Nenhuma turma encontrada</p>
      <button @click="openCreateModal" class="text-blue-500 text-sm mt-2 inline-block font-medium">
        + Criar primeira turma
      </button>
    </div>

    <!-- Cards Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div v-for="turma in turmas" :key="turma.id" class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden hover:shadow-md transition-all group">
        <!-- Card Header -->
        <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center">
              <Icon name="heroicons:academic-cap-20-solid" class="w-4 h-4 text-blue-600 dark:text-blue-400" />
            </div>
            <span class="font-mono text-xs font-medium text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded">{{ turma.codigo }}</span>
          </div>
          <span :class="turma.status === 'ativa' ? 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-500/20 dark:text-gray-400'" class="px-2 py-0.5 text-xs font-medium rounded-full">
            {{ turma.status === 'ativa' ? 'Ativa' : 'Inativa' }}
          </span>
        </div>
        
        <!-- Card Body -->
        <div class="p-4">
          <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-3">{{ turma.nome }}</h3>
          
          <div class="space-y-2.5">
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-500 flex items-center gap-1">
                <Icon name="heroicons:clock-20-solid" class="w-3.5 h-3.5" />
                Período
              </span>
              <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ turma.periodo || 'Não definido' }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-500 flex items-center gap-1">
                <Icon name="heroicons:user-group-20-solid" class="w-3.5 h-3.5" />
                Capacidade
              </span>
              <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ turma.capacidade }} alunos</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-500 flex items-center gap-1">
                <Icon name="heroicons:academic-cap-20-solid" class="w-3.5 h-3.5" />
                Professor
              </span>
              <span class="text-sm font-medium text-slate-700 dark:text-slate-300 truncate max-w-[180px]">{{ turma.professor?.name || 'Não definido' }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-500 flex items-center gap-1">
                <Icon name="heroicons:calendar-20-solid" class="w-3.5 h-3.5" />
                Criada em
              </span>
              <span class="text-sm text-slate-600 dark:text-slate-400">{{ formatDate(turma.created_at) }}</span>
            </div>
          </div>
        </div>
        
        <!-- Card Footer -->
        <div class="p-3 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex justify-end gap-2">
          <button @click="openEditModal(turma)" class="p-1.5 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-500/10 transition-colors" title="Editar">
            <Icon name="heroicons:pencil-square-20-solid" class="w-4 h-4" />
          </button>
          <button @click="openDeleteModal(turma)" class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors" title="Excluir">
            <Icon name="heroicons:trash-20-solid" class="w-4 h-4" />
          </button>
        </div>
      </div>
    </div>

    <!-- Modal de Criar/Editar Turma -->
    <Transition name="modal">
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="closeModal">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto animate-modal">
          <!-- Header -->
          <div class="sticky top-0 z-10 bg-gradient-to-r from-blue-500 to-blue-600 p-5 text-white rounded-t-2xl">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                  <Icon :name="isEditing ? 'heroicons:pencil-square-20-solid' : 'heroicons:plus-20-solid'" class="w-5 h-5" />
                </div>
                <div>
                  <h2 class="text-xl font-bold">{{ isEditing ? 'Editar Turma' : 'Nova Turma' }}</h2>
                  <p class="text-blue-100 text-xs mt-0.5">{{ isEditing ? 'Altere as informações da turma' : 'Preencha os dados para cadastrar' }}</p>
                </div>
              </div>
              <button @click="closeModal" class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 transition-colors flex items-center justify-center">
                <Icon name="heroicons:x-mark-20-solid" class="w-5 h-5" />
              </button>
            </div>
          </div>

          <!-- Formulário -->
          <form @submit.prevent="submitForm" class="p-6 space-y-5">
            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                Nome da Turma <span class="text-red-500">*</span>
              </label>
              <input 
                v-model="form.nome" 
                type="text" 
                required 
                placeholder="Ex: 3° Ano Informática"
                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
              >
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                Código <span class="text-red-500">*</span>
              </label>
              <input 
                v-model="form.codigo" 
                type="text" 
                required 
                placeholder="Ex: INF3A, MAT2B"
                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
              >
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                  Ano
                </label>
                <input 
                  v-model="form.ano" 
                  type="text" 
                  placeholder="Ex: 2024"
                  class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-blue-500"
                >
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                  Período
                </label>
                <select v-model="form.periodo" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-blue-500">
                  <option value="">Selecione</option>
                  <option value="Manhã">🌅 Manhã</option>
                  <option value="Tarde">☀️ Tarde</option>
                  <option value="Noite">🌙 Noite</option>
                  <option value="Integral">📚 Integral</option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                  Capacidade
                </label>
                <input 
                  v-model="form.capacidade" 
                  type="number" 
                  min="1" 
                  max="100"
                  class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-blue-500"
                >
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                  Status
                </label>
                <div class="flex gap-4 pt-2">
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" value="ativa" v-model="form.status" class="w-4 h-4 text-blue-500">
                    <span class="text-sm">Ativa</span>
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" value="inativa" v-model="form.status" class="w-4 h-4 text-gray-500">
                    <span class="text-sm">Inativa</span>
                  </label>
                </div>
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                Professor Responsável
              </label>
              <select v-model="form.professor_id" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-blue-500">
                <option value="">Nenhum</option>
                <option v-for="prof in professores" :key="prof.id" :value="prof.id">{{ prof.name }}</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                Observações
              </label>
              <textarea 
                v-model="form.observacoes" 
                rows="2" 
                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-blue-500 resize-none" 
                placeholder="Informações adicionais sobre a turma"
              ></textarea>
            </div>

            <!-- Botões -->
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
              <button type="button" @click="closeModal" class="px-5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors font-medium">
                Cancelar
              </button>
              <button type="submit" :disabled="submitting" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white transition-all flex items-center gap-2 font-medium shadow-md">
                <Icon v-if="submitting" name="heroicons:arrow-path-20-solid" class="w-4 h-4 animate-spin" />
                <Icon v-else name="heroicons:check-20-solid" class="w-4 h-4" />
                {{ submitting ? 'Salvando...' : (isEditing ? 'Atualizar' : 'Criar Turma') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </Transition>

    <!-- Modal de Exclusão -->
    <Transition name="modal">
      <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="showDeleteModal = false">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full animate-modal">
          <div class="bg-gradient-to-r from-red-500 to-red-600 p-5 text-white rounded-t-2xl">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                <Icon name="heroicons:exclamation-triangle-20-solid" class="w-5 h-5" />
              </div>
              <div>
                <h2 class="text-xl font-bold">Confirmar Exclusão</h2>
                <p class="text-red-100 text-xs">Esta ação não pode ser desfeita</p>
              </div>
            </div>
          </div>

          <div class="p-6 text-center">
            <div class="w-20 h-20 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center mx-auto mb-4">
              <Icon name="heroicons:trash-20-solid" class="w-10 h-10 text-red-500" />
            </div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Excluir Turma</h3>
            <p class="text-slate-500 dark:text-slate-400 mb-2">
              Tem certeza que deseja excluir a turma:
            </p>
            <p class="font-bold text-lg text-slate-900 dark:text-white bg-slate-100 dark:bg-slate-800 py-2 px-4 rounded-xl inline-block">
              {{ deleteTurma?.nome }}
            </p>
            <p class="text-xs text-slate-400 mt-3">Código: {{ deleteTurma?.codigo }}</p>
          </div>

          <div class="border-t border-slate-100 dark:border-slate-800 p-5 bg-slate-50 dark:bg-slate-800/30 rounded-b-2xl flex justify-end gap-3">
            <button @click="showDeleteModal = false" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors font-medium">
              Cancelar
            </button>
            <button @click="confirmDelete" :disabled="deleting" class="flex-1 px-4 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white transition-all flex items-center justify-center gap-2 font-medium shadow-md">
              <Icon v-if="deleting" name="heroicons:arrow-path-20-solid" class="w-4 h-4 animate-spin" />
              <Icon v-else name="heroicons:check-20-solid" class="w-4 h-4" />
              {{ deleting ? 'Excluindo...' : 'Confirmar' }}
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useApi } from '~/composables/useApi'

definePageMeta({
  middleware: 'auth',
  layout: 'admin'
})

const { get, post, put, del } = useApi()

const turmas = ref([])
const professores = ref([])
const loading = ref(false)
const filters = reactive({ search: '', status: '' })

const showModal = ref(false)
const showDeleteModal = ref(false)
const isEditing = ref(false)
const submitting = ref(false)
const deleting = ref(false)
const editId = ref(null)
const deleteTurma = ref(null)

const form = reactive({
  nome: '',
  codigo: '',
  ano: '',
  periodo: '',
  capacidade: 40,
  professor_id: '',
  status: 'ativa',
  observacoes: ''
})

const formatDate = (date) => {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('pt-BR')
}

const resetForm = () => {
  form.nome = ''
  form.codigo = ''
  form.ano = ''
  form.periodo = ''
  form.capacidade = 40
  form.professor_id = ''
  form.status = 'ativa'
  form.observacoes = ''
  editId.value = null
  isEditing.value = false
}

const loadProfessores = async () => {
  try {
    const response = await get('/usuarios', { role: 'professor' })
    professores.value = response
  } catch (error) {
    console.error('Erro ao carregar professores:', error)
  }
}

const loadData = async () => {
  loading.value = true
  try {
    const params = {}
    if (filters.search) params.search = filters.search
    if (filters.status) params.status = filters.status
    const response = await get('/turmas', params)
    turmas.value = response.data
  } catch (error) {
    console.error('Erro ao carregar turmas:', error)
  } finally {
    loading.value = false
  }
}

const openCreateModal = () => {
  resetForm()
  showModal.value = true
}

const openEditModal = (turma) => {
  resetForm()
  form.nome = turma.nome
  form.codigo = turma.codigo
  form.ano = turma.ano || ''
  form.periodo = turma.periodo || ''
  form.capacidade = turma.capacidade || 40
  form.professor_id = turma.professor_id || ''
  form.status = turma.status
  form.observacoes = turma.observacoes || ''
  editId.value = turma.id
  isEditing.value = true
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  resetForm()
}

const submitForm = async () => {
  if (!form.nome || !form.codigo) return
  
  submitting.value = true
  try {
    if (isEditing.value) {
      await put(`/turmas/${editId.value}`, { ...form })
    } else {
      await post('/turmas', { ...form })
    }
    closeModal()
    await loadData()
  } catch (error) {
    console.error('Erro ao salvar:', error)
  } finally {
    submitting.value = false
  }
}

const openDeleteModal = (turma) => {
  deleteTurma.value = turma
  showDeleteModal.value = true
}

const confirmDelete = async () => {
  if (!deleteTurma.value) return
  
  deleting.value = true
  try {
    await del(`/turmas/${deleteTurma.value.id}`)
    showDeleteModal.value = false
    deleteTurma.value = null
    await loadData()
  } catch (error) {
    console.error('Erro ao excluir:', error)
  } finally {
    deleting.value = false
  }
}

const applyFilters = () => {
  loadData()
}

onMounted(() => {
  loadData()
  loadProfessores()
})
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.animate-modal {
  animation: modalSlide 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes modalSlide {
  from {
    opacity: 0;
    transform: scale(0.95) translateY(10px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}
</style>