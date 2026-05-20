<template>
  <div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
      <div class="flex items-center gap-4 mb-3">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-xl shadow-red-500/30 ring-2 ring-red-400/30">
          <Icon name="heroicons:document-plus-20-solid" class="w-7 h-7 text-white" />
        </div>
        <div>
          <h1 class="text-3xl font-black bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300 bg-clip-text text-transparent">Nova Autorização de Saída</h1>
          <p class="text-slate-500 dark:text-slate-400 mt-1">Preencha o formulário abaixo para autorizar a saída de um aluno</p>
        </div>
      </div>
    </div>

    <form @submit.prevent="submitForm" class="space-y-6">
      <!-- Dados do Aluno -->
      <div class="group bg-white dark:bg-slate-900 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-800 overflow-hidden transition-all duration-300 hover:shadow-xl">
        <div class="relative">
          <div class="absolute inset-0 bg-gradient-to-r from-red-500/5 to-transparent"></div>
          <div class="relative p-6">
            <div class="flex items-center gap-3 mb-6 pb-3 border-b-2 border-red-500/20">
              <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-100 to-red-200 dark:from-red-500/20 dark:to-red-600/20 flex items-center justify-center">
                <Icon name="heroicons:user-20-solid" class="w-5 h-5 text-red-500" />
              </div>
              <h2 class="text-xl font-bold text-slate-800 dark:text-white">Dados do Aluno</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="group/input">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                  <Icon name="heroicons:user-20-solid" class="w-4 h-4 text-red-500" />
                  Nome do Aluno <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.aluno_nome"
                  type="text"
                  class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                  placeholder="Digite o nome completo"
                  required
                />
                <div class="h-1 mt-1 rounded-full bg-gradient-to-r from-red-500 to-red-600 scale-x-0 group-hover/input:scale-x-100 transition-transform origin-left"></div>
              </div>

              <div class="group/input">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                  <Icon name="heroicons:users-20-solid" class="w-4 h-4 text-red-500" />
                  Turma <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.turma"
                  type="text"
                  class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                  placeholder="Ex: 3° Informática A"
                  required
                />
                <div class="h-1 mt-1 rounded-full bg-gradient-to-r from-red-500 to-red-600 scale-x-0 group-hover/input:scale-x-100 transition-transform origin-left"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Dados da Autorização -->
      <div class="group bg-white dark:bg-slate-900 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-800 overflow-hidden transition-all duration-300 hover:shadow-xl">
        <div class="relative p-6">
          <div class="flex items-center gap-3 mb-6 pb-3 border-b-2 border-amber-500/20">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-500/20 dark:to-amber-600/20 flex items-center justify-center">
              <Icon name="heroicons:clock-20-solid" class="w-5 h-5 text-amber-500" />
            </div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white">Dados da Autorização</h2>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="group/input">
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                <Icon name="heroicons:clock-20-solid" class="w-4 h-4 text-amber-500" />
                Horário de Saída <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.horario_saida"
                type="time"
                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200"
                required
              />
              <div class="h-1 mt-1 rounded-full bg-gradient-to-r from-amber-500 to-amber-600 scale-x-0 group-hover/input:scale-x-100 transition-transform origin-left"></div>
            </div>

            <div class="group/input">
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                <Icon name="heroicons:academic-cap-20-solid" class="w-4 h-4 text-amber-500" />
                Nº da Aula <span class="text-red-500">*</span>
              </label>
              <select
                v-model="form.aula_numero"
                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200 cursor-pointer"
                required
              >
                <option value="">Selecione a aula</option>
                <option v-for="n in 5" :key="n" :value="n">{{ n }}ª Aula</option>
              </select>
              <div class="h-1 mt-1 rounded-full bg-gradient-to-r from-amber-500 to-amber-600 scale-x-0 group-hover/input:scale-x-100 transition-transform origin-left"></div>
            </div>

            <div class="group/input">
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                <Icon name="heroicons:user-group-20-solid" class="w-4 h-4 text-amber-500" />
                Professor Responsável <span class="text-red-500">*</span>
              </label>
              <SelectSearch
                v-model="form.professor_id"
                :options="professores"
                option-label="name"
                option-value="id"
                placeholder="Selecione o professor"
                :loading="loadingProfessores"
                class="w-full"
              />
            </div>
          </div>

          <div class="mt-6 group/input">
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
              <Icon name="heroicons:document-text-20-solid" class="w-4 h-4 text-amber-500" />
              Motivo da Saída
            </label>
            <textarea
              v-model="form.motivo_saida"
              rows="3"
              class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200 resize-none"
              placeholder="Informe o motivo da saída (opcional)"
            ></textarea>
            <div class="h-1 mt-1 rounded-full bg-gradient-to-r from-amber-500 to-amber-600 scale-x-0 group-hover/input:scale-x-100 transition-transform origin-left"></div>
          </div>
        </div>
      </div>

      <!-- Contato do Responsável -->
      <div class="group bg-white dark:bg-slate-900 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-800 overflow-hidden transition-all duration-300 hover:shadow-xl">
        <div class="relative p-6">
          <div class="flex items-center gap-3 mb-6 pb-3 border-b-2 border-emerald-500/20">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-500/20 dark:to-emerald-600/20 flex items-center justify-center">
              <Icon name="heroicons:envelope-20-solid" class="w-5 h-5 text-emerald-500" />
            </div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white">Notificação do Responsável</h2>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="group/input">
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                <Icon name="heroicons:envelope-20-solid" class="w-4 h-4 text-emerald-500" />
                E-mail do Responsável <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.responsavel_contato"
                type="email"
                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200"
                placeholder="responsavel@email.com"
                required
              />
              <div class="h-1 mt-1 rounded-full bg-gradient-to-r from-emerald-500 to-emerald-600 scale-x-0 group-hover/input:scale-x-100 transition-transform origin-left"></div>
              <p class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                <Icon name="heroicons:information-circle-20-solid" class="w-3 h-3" />
                O responsável receberá uma notificação quando a saída for registrada
              </p>
            </div>

            <div class="group/input">
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                <Icon name="heroicons:chat-bubble-left-20-solid" class="w-4 h-4 text-emerald-500" />
                Observações (opcional)
              </label>
              <input
                v-model="form.observacoes"
                type="text"
                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200"
                placeholder="Informações adicionais"
              />
              <div class="h-1 mt-1 rounded-full bg-gradient-to-r from-emerald-500 to-emerald-600 scale-x-0 group-hover/input:scale-x-100 transition-transform origin-left"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pré-visualização -->
      <Transition name="slide-down">
        <div v-if="showPreview" class="bg-gradient-to-r from-red-50 via-amber-50 to-emerald-50 dark:from-red-500/10 dark:via-amber-500/10 dark:to-emerald-500/10 rounded-2xl border-2 border-red-200 dark:border-red-500/30 overflow-hidden">
          <div class="p-6">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-md">
                <Icon name="heroicons:eye-20-solid" class="w-5 h-5 text-white" />
              </div>
              <h2 class="text-xl font-bold text-slate-800 dark:text-white">Pré-visualização da Autorização</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
              <div class="bg-white/50 dark:bg-slate-800/50 rounded-xl p-4 backdrop-blur-sm">
                <p class="text-slate-500 text-xs mb-1 flex items-center gap-1">
                  <Icon name="heroicons:user-20-solid" class="w-3 h-3" />
                  Aluno
                </p>
                <p class="font-semibold text-slate-900 dark:text-white text-lg">{{ form.aluno_nome || '-' }}</p>
              </div>
              <div class="bg-white/50 dark:bg-slate-800/50 rounded-xl p-4 backdrop-blur-sm">
                <p class="text-slate-500 text-xs mb-1 flex items-center gap-1">
                  <Icon name="heroicons:users-20-solid" class="w-3 h-3" />
                  Turma
                </p>
                <p class="font-semibold text-slate-900 dark:text-white text-lg">{{ form.turma || '-' }}</p>
              </div>
              <div class="bg-white/50 dark:bg-slate-800/50 rounded-xl p-4 backdrop-blur-sm">
                <p class="text-slate-500 text-xs mb-1 flex items-center gap-1">
                  <Icon name="heroicons:clock-20-solid" class="w-3 h-3" />
                  Horário de Saída
                </p>
                <p class="font-semibold text-slate-900 dark:text-white text-lg">{{ form.horario_saida || '-' }}</p>
              </div>
              <div class="bg-white/50 dark:bg-slate-800/50 rounded-xl p-4 backdrop-blur-sm">
                <p class="text-slate-500 text-xs mb-1 flex items-center gap-1">
                  <Icon name="heroicons:academic-cap-20-solid" class="w-3 h-3" />
                  Aula
                </p>
                <p class="font-semibold text-slate-900 dark:text-white text-lg">{{ form.aula_numero ? `${form.aula_numero}ª Aula` : '-' }}</p>
              </div>
              <div class="bg-white/50 dark:bg-slate-800/50 rounded-xl p-4 backdrop-blur-sm">
                <p class="text-slate-500 text-xs mb-1 flex items-center gap-1">
                  <Icon name="heroicons:user-group-20-solid" class="w-3 h-3" />
                  Professor
                </p>
                <p class="font-semibold text-slate-900 dark:text-white text-lg">{{ selectedProfessorName || '-' }}</p>
              </div>
              <div class="bg-white/50 dark:bg-slate-800/50 rounded-xl p-4 backdrop-blur-sm">
                <p class="text-slate-500 text-xs mb-1 flex items-center gap-1">
                  <Icon name="heroicons:envelope-20-solid" class="w-3 h-3" />
                  E-mail do Responsável
                </p>
                <p class="font-semibold text-slate-900 dark:text-white text-lg truncate">{{ form.responsavel_contato || '-' }}</p>
              </div>
              <div v-if="form.motivo_saida" class="md:col-span-2 bg-white/50 dark:bg-slate-800/50 rounded-xl p-4 backdrop-blur-sm">
                <p class="text-slate-500 text-xs mb-1 flex items-center gap-1">
                  <Icon name="heroicons:document-text-20-solid" class="w-3 h-3" />
                  Motivo
                </p>
                <p class="text-slate-700 dark:text-slate-300">{{ form.motivo_saida }}</p>
              </div>
            </div>
          </div>
        </div>
      </Transition>

      <!-- Botões -->
      <div class="flex justify-end gap-4 pt-4">
        <button 
          @click="showPreview = !showPreview" 
          type="button" 
          class="px-6 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all duration-200 flex items-center gap-2"
        >
          <Icon :name="showPreview ? 'heroicons:eye-slash-20-solid' : 'heroicons:eye-20-solid'" class="w-5 h-5" />
          {{ showPreview ? 'Ocultar Prévia' : 'Ver Prévia' }}
        </button>
        
        <NuxtLink to="/admin/authorizations">
          <button type="button" class="px-6 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all duration-200 flex items-center gap-2">
            <Icon name="heroicons:x-mark-20-solid" class="w-5 h-5" />
            Cancelar
          </button>
        </NuxtLink>
        
        <button 
          type="submit" 
          :disabled="submitting"
          class="px-8 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-xl transition-all duration-300 flex items-center gap-2 shadow-lg shadow-red-500/30 font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <Icon v-if="submitting" name="heroicons:arrow-path-20-solid" class="w-5 h-5 animate-spin" />
          <Icon v-else name="heroicons:paper-airplane-20-solid" class="w-5 h-5" />
          {{ submitting ? 'Enviando...' : 'Enviar Autorização' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthorizations } from '~/composables/useAuthorizations'
import { useToast } from '~/composables/useToast'
import SelectSearch from '~/components/common/SelectSearch.vue'

definePageMeta({
  middleware: 'auth',
  layout: 'admin'
})

const { create, fetchProfessores } = useAuthorizations()
const { show: toast } = useToast()

const form = ref({
  aluno_nome: '',
  turma: '',
  motivo_saida: '',
  horario_saida: '',
  aula_numero: '',
  professor_id: '',
  responsavel_contato: '',
  observacoes: ''
})

const professores = ref([])
const loadingProfessores = ref(false)
const submitting = ref(false)
const showPreview = ref(false)

const selectedProfessorName = computed(() => {
  const professor = professores.value.find(p => p.id === form.value.professor_id)
  return professor ? professor.name : null
})

const loadProfessores = async () => {
  loadingProfessores.value = true
  try {
    professores.value = await fetchProfessores()
  } catch (error) {
    console.error('Erro ao carregar professores:', error)
    toast('Erro ao carregar lista de professores', 'error')
  } finally {
    loadingProfessores.value = false
  }
}

const submitForm = async () => {
  if (!form.value.aluno_nome || !form.value.turma || !form.value.horario_saida || !form.value.aula_numero || !form.value.professor_id || !form.value.responsavel_contato) {
    toast('Preencha todos os campos obrigatórios', 'warning')
    return
  }
  
  submitting.value = true
  try {
    await create(form.value)
    toast('Autorização criada com sucesso! Professor foi notificado.', 'success')
    
    form.value = {
      aluno_nome: '',
      turma: '',
      motivo_saida: '',
      horario_saida: '',
      aula_numero: '',
      professor_id: '',
      responsavel_contato: '',
      observacoes: ''
    }
    showPreview.value = false
  } catch (error) {
    console.error('Erro ao criar autorização:', error)
    const message = error.response?.data?.message || 'Erro ao criar autorização'
    toast(message, 'error')
  } finally {
    submitting.value = false
  }
}

onMounted(() => {
  loadProfessores()
})
</script>

<style scoped>
.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.slide-down-enter-from,
.slide-down-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}
</style>