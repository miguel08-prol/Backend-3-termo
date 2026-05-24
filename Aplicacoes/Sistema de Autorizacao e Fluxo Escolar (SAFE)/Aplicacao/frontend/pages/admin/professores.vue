<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-md">
            <Icon name="heroicons:academic-cap-20-solid" class="w-6 h-6 text-white" />
          </div>
          <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Professores</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Gerencie todos os professores da escola</p>
          </div>
        </div>
      </div>
      
      <button @click="openCreateModal" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition-colors flex items-center gap-2 font-medium shadow-sm">
        <Icon name="heroicons:plus-20-solid" class="w-5 h-5" />
        Novo Professor
      </button>
    </div>

    <!-- Busca -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4">
      <div class="relative">
        <Icon name="heroicons:magnifying-glass-20-solid" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
        <input 
          v-model="filters.search" 
          type="text" 
          placeholder="Buscar por nome, e-mail ou departamento..." 
          class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
          @input="applyFilters"
        />
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading && professores.length === 0" class="flex justify-center py-16">
      <div class="flex flex-col items-center gap-3">
        <Icon name="heroicons:arrow-path-20-solid" class="w-8 h-8 animate-spin text-emerald-500" />
        <p class="text-slate-500">Carregando professores...</p>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else-if="professores.length === 0" class="text-center py-16 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800">
      <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-3">
        <Icon name="heroicons:academic-cap-20-solid" class="w-8 h-8 text-slate-300 dark:text-slate-600" />
      </div>
      <p class="text-slate-500">Nenhum professor encontrado</p>
      <button @click="openCreateModal" class="text-emerald-500 text-sm mt-2 inline-block font-medium">
        + Cadastrar primeiro professor
      </button>
    </div>

    <!-- Cards Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div v-for="prof in professores" :key="prof.id" class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden hover:shadow-md transition-all group">
        <!-- Card Header -->
        <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
          <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-500/20 dark:to-emerald-600/20 flex items-center justify-center">
            <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
              {{ getInitials(prof.name) }}
            </span>
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="font-bold text-slate-900 dark:text-white truncate">{{ prof.name }}</h3>
            <p class="text-xs text-slate-400 truncate">{{ prof.email }}</p>
          </div>
        </div>
        
        <!-- Card Body -->
        <div class="p-4 space-y-2">
          <div class="flex items-center gap-2 text-sm">
            <Icon name="heroicons:device-phone-mobile-20-solid" class="w-4 h-4 text-slate-400" />
            <span class="text-slate-600 dark:text-slate-300">{{ prof.telefone || 'Telefone não informado' }}</span>
          </div>
          <div class="flex items-center gap-2 text-sm">
            <Icon name="heroicons:building-office-20-solid" class="w-4 h-4 text-slate-400" />
            <span class="text-slate-600 dark:text-slate-300">{{ prof.departamento || 'Departamento não informado' }}</span>
          </div>
          <div class="flex items-center gap-2 text-sm">
            <Icon name="heroicons:calendar-20-solid" class="w-4 h-4 text-slate-400" />
            <span class="text-slate-600 dark:text-slate-300">Cadastrado em {{ formatDate(prof.created_at) }}</span>
          </div>
        </div>
        
        <!-- Card Footer -->
        <div class="p-3 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex justify-end gap-2">
          <button @click="openEditModal(prof)" class="p-1.5 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-500/10 transition-colors" title="Editar">
            <Icon name="heroicons:pencil-square-20-solid" class="w-4 h-4" />
          </button>
          <button @click="openDeleteModal(prof)" class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors" title="Excluir">
            <Icon name="heroicons:trash-20-solid" class="w-4 h-4" />
          </button>
        </div>
      </div>
    </div>

    <!-- Modal de Criar/Editar Professor -->
    <Transition name="modal">
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="closeModal">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto animate-modal">
          <!-- Header -->
          <div class="sticky top-0 z-10 bg-gradient-to-r from-emerald-500 to-emerald-600 p-5 text-white rounded-t-2xl">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                  <Icon :name="isEditing ? 'heroicons:pencil-square-20-solid' : 'heroicons:plus-20-solid'" class="w-5 h-5" />
                </div>
                <div>
                  <h2 class="text-xl font-bold">{{ isEditing ? 'Editar Professor' : 'Novo Professor' }}</h2>
                  <p class="text-emerald-100 text-xs mt-0.5">{{ isEditing ? 'Altere as informações do professor' : 'Preencha os dados para cadastrar' }}</p>
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
                Nome Completo <span class="text-red-500">*</span>
              </label>
              <input 
                v-model="form.name" 
                type="text" 
                required 
                placeholder="Ex: João Silva"
                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
              >
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                E-mail <span class="text-red-500">*</span>
              </label>
              <input 
                v-model="form.email" 
                type="email" 
                required 
                placeholder="professor@escola.com"
                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
              >
            </div>

            <div v-if="!isEditing">
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                Senha <span class="text-red-500">*</span>
              </label>
              <div class="relative">
                <input 
                  v-model="form.password" 
                    :type="showPassword ? 'text' : 'password'" 
                  required 
                  placeholder="Mínimo 6 caracteres"
                  class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all pr-10"
                >
                <button 
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-emerald-500 transition-colors"
                >
                  <Icon v-if="showPassword" name="heroicons:eye-20-solid" class="w-5 h-5" />
                  <Icon v-else name="heroicons:eye-slash-20-solid" class="w-5 h-5" />
                </button>
              </div>
              <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                <Icon name="heroicons:information-circle-20-solid" class="w-3 h-3" />
                A senha deve ter no mínimo 6 caracteres
              </p>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                Telefone
              </label>
              <input 
                v-model="form.telefone" 
                type="text" 
                placeholder="(11) 99999-9999"
                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                @input="formatPhone"
                maxlength="15"
              >
              <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                <Icon name="heroicons:device-phone-mobile-20-solid" class="w-3 h-3" />
                Formato: (DD) 9XXXX-XXXX
              </p>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                Departamento
              </label>
              <input 
                v-model="form.departamento" 
                type="text" 
                placeholder="Ex: Informática, Matemática, Português..."
                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
              >
            </div>

            <!-- Botões -->
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
              <button type="button" @click="closeModal" class="px-5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors font-medium">
                Cancelar
              </button>
              <button type="submit" :disabled="submitting" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white transition-all flex items-center gap-2 font-medium shadow-md">
                <Icon v-if="submitting" name="heroicons:arrow-path-20-solid" class="w-4 h-4 animate-spin" />
                <Icon v-else name="heroicons:check-20-solid" class="w-4 h-4" />
                {{ submitting ? 'Salvando...' : (isEditing ? 'Atualizar' : 'Cadastrar') }}
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
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Excluir Professor</h3>
            <p class="text-slate-500 dark:text-slate-400 mb-2">
              Tem certeza que deseja excluir o professor:
            </p>
            <p class="font-bold text-lg text-slate-900 dark:text-white bg-slate-100 dark:bg-slate-800 py-2 px-4 rounded-xl inline-block">
              {{ deleteProfessor?.name }}
            </p>
            <p class="text-xs text-slate-400 mt-3">{{ deleteProfessor?.email }}</p>
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

const professores = ref([])
const loading = ref(false)
const filters = reactive({ search: '' })

const showModal = ref(false)
const showDeleteModal = ref(false)
const isEditing = ref(false)
const submitting = ref(false)
const deleting = ref(false)
const editId = ref(null)
const deleteProfessor = ref(null)
const showPassword = ref(false)

const form = reactive({
  name: '',
  email: '',
  password: '',
  telefone: '',
  departamento: ''
})

const getInitials = (name) => {
  if (!name) return '?'
  return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2)
}

const formatDate = (date) => {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('pt-BR')
}

// Máscara para telefone
const formatPhone = (event) => {
  let value = form.telefone.replace(/\D/g, '')
  if (value.length > 11) value = value.slice(0, 11)
  
  if (value.length <= 2) {
    form.telefone = value
  } else if (value.length <= 6) {
    form.telefone = `(${value.slice(0, 2)}) ${value.slice(2)}`
  } else if (value.length <= 10) {
    form.telefone = `(${value.slice(0, 2)}) ${value.slice(2, 6)}-${value.slice(6)}`
  } else {
    form.telefone = `(${value.slice(0, 2)}) ${value.slice(2, 7)}-${value.slice(7, 11)}`
  }
}

const resetForm = () => {
  form.name = ''
  form.email = ''
  form.password = ''
  form.telefone = ''
  form.departamento = ''
  editId.value = null
  isEditing.value = false
  showPassword.value = false
}

const loadData = async () => {
  loading.value = true
  try {
    const params = filters.search ? { search: filters.search } : {}
    const response = await get('/usuarios', { ...params, role: 'professor' })
    professores.value = response
  } catch (error) {
    console.error('Erro ao carregar professores:', error)
  } finally {
    loading.value = false
  }
}

const openCreateModal = () => {
  resetForm()
  showModal.value = true
}

const openEditModal = (prof) => {
  resetForm()
  form.name = prof.name
  form.email = prof.email
  form.telefone = prof.telefone || ''
  form.departamento = prof.departamento || ''
  editId.value = prof.id
  isEditing.value = true
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  resetForm()
}

const submitForm = async () => {
  if (!form.name || !form.email) return
  
  // Validação da senha apenas para novo cadastro
  if (!isEditing.value) {
    if (!form.password) {
      // Toast de erro (você pode implementar seu toast aqui)
      console.error('Senha é obrigatória')
      return
    }
    if (form.password.length < 6) {
      console.error('A senha deve ter no mínimo 6 caracteres')
      return
    }
  }
  
  submitting.value = true
  try {
    if (isEditing.value) {
      // Para edição, não enviar senha se estiver vazia
      const updateData = {
        name: form.name,
        email: form.email,
        telefone: form.telefone,
        departamento: form.departamento
      }
      await put(`/usuarios/${editId.value}`, updateData)
    } else {
      await post('/usuarios', { 
        name: form.name,
        email: form.email,
        password: form.password,
        telefone: form.telefone,
        departamento: form.departamento,
        role: 'professor'
      })
    }
    closeModal()
    await loadData()
  } catch (error) {
    console.error('Erro ao salvar:', error)
  } finally {
    submitting.value = false
  }
}

const openDeleteModal = (prof) => {
  deleteProfessor.value = prof
  showDeleteModal.value = true
}

const confirmDelete = async () => {
  if (!deleteProfessor.value) return
  
  deleting.value = true
  try {
    await del(`/usuarios/${deleteProfessor.value.id}`)
    showDeleteModal.value = false
    deleteProfessor.value = null
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