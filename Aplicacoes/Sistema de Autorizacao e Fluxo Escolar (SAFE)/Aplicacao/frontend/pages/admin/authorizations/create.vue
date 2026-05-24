<template>
  <div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
      <div class="flex items-center gap-3 mb-2">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-md">
          <Icon name="heroicons:document-plus-20-solid" class="w-6 h-6 text-white" />
        </div>
        <div>
          <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Nova Autorização</h1>
          <p class="text-slate-500 dark:text-slate-400">Preencha o formulário abaixo</p>
        </div>
      </div>
    </div>

    <form @submit.prevent="submitForm" class="space-y-5">
      <!-- Tipo de Autorização -->
      <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="p-5">
          <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-red-100 to-red-200 dark:from-red-500/20 dark:to-red-600/20 flex items-center justify-center">
              <Icon name="heroicons:arrow-path-20-solid" class="w-4 h-4 text-red-500" />
            </div>
            <h2 class="text-lg font-bold text-slate-800 dark:text-white">Tipo de Autorização</h2>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <label 
              class="flex items-center justify-between p-4 rounded-lg border-2 cursor-pointer transition-all"
              :class="form.tipo === 'saida' 
                ? 'border-red-500 bg-red-50 dark:bg-red-500/10' 
                : 'border-slate-200 dark:border-slate-700 hover:border-red-300'"
            >
              <div class="flex items-center gap-3">
                <div :class="[
                  'w-10 h-10 rounded-lg flex items-center justify-center',
                  form.tipo === 'saida' ? 'bg-red-500' : 'bg-slate-200 dark:bg-slate-700'
                ]">
                  <Icon name="heroicons:arrow-right-start-on-rectangle-20-solid" class="w-5 h-5 text-white" />
                </div>
                <div>
                  <p class="font-bold text-slate-800 dark:text-white">Saída</p>
                  <p class="text-xs text-slate-500">Aluno está saindo da escola</p>
                </div>
              </div>
              <input type="radio" value="saida" v-model="form.tipo" class="w-5 h-5 accent-red-500" />
            </label>

            <label 
              class="flex items-center justify-between p-4 rounded-lg border-2 cursor-pointer transition-all"
              :class="form.tipo === 'entrada' 
                ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-500/10' 
                : 'border-slate-200 dark:border-slate-700 hover:border-emerald-300'"
            >
              <div class="flex items-center gap-3">
                <div :class="[
                  'w-10 h-10 rounded-lg flex items-center justify-center',
                  form.tipo === 'entrada' ? 'bg-emerald-500' : 'bg-slate-200 dark:bg-slate-700'
                ]">
                  <Icon name="heroicons:arrow-left-start-on-rectangle-20-solid" class="w-5 h-5 text-white" />
                </div>
                <div>
                  <p class="font-bold text-slate-800 dark:text-white">Entrada</p>
                  <p class="text-xs text-slate-500">Aluno está retornando à escola</p>
                </div>
              </div>
              <input type="radio" value="entrada" v-model="form.tipo" class="w-5 h-5 accent-emerald-500" />
            </label>
          </div>
        </div>
      </div>

      <!-- Dados do Aluno -->
      <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="p-5">
          <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-red-100 to-red-200 dark:from-red-500/20 dark:to-red-600/20 flex items-center justify-center">
              <Icon name="heroicons:user-20-solid" class="w-4 h-4 text-red-500" />
            </div>
            <h2 class="text-lg font-bold text-slate-800 dark:text-white">Dados do Aluno</h2>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">
                Nome do Aluno <span class="text-red-500">*</span>
              </label>
              <input v-model="form.aluno_nome" type="text" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-red-500" placeholder="Digite o nome completo" />
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">
                Turma <span class="text-red-500">*</span>
              </label>
              <div class="relative">
                <input type="text" list="turmas-list" v-model="form.turma" @input="handleTurmaSearch" placeholder="Digite o nome da turma..." class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-red-500" autocomplete="off" />
                <datalist id="turmas-list">
                  <option v-for="turma in turmas" :key="turma.id" :value="turma.nome">{{ turma.nome }} ({{ turma.codigo }})</option>
                </datalist>
                <input type="hidden" v-model="form.turma_id" />
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">
                Turno <span class="text-red-500">*</span>
              </label>
              <select v-model="form.turno" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-red-500">
                <option value="manha">🌅 Manhã (07:30 - 11:30)</option>
                <option value="tarde">☀️ Tarde (13:00 - 17:00)</option>
                <option value="noite">🌙 Noite (18:30 - 22:30)</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">
                Professor Responsável <span class="text-red-500">*</span>
              </label>
              <div class="relative">
                <input type="text" list="professores-list" v-model="form.professor_nome" @input="handleProfessorSearch" placeholder="Digite o nome do professor..." class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-red-500" autocomplete="off" />
                <datalist id="professores-list">
                  <option v-for="prof in professores" :key="prof.id" :value="prof.name">{{ prof.name }} - {{ prof.email }}</option>
                </datalist>
                <input type="hidden" v-model="form.professor_id" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Dados da Autorização -->
      <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="p-5">
          <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-500/20 dark:to-amber-600/20 flex items-center justify-center">
              <Icon name="heroicons:clock-20-solid" class="w-4 h-4 text-amber-500" />
            </div>
            <h2 class="text-lg font-bold text-slate-800 dark:text-white">Dados da Autorização</h2>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">
                Horário <span class="text-red-500">*</span>
              </label>
              <input v-model="form.horario" type="time" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-amber-500" />
              <p class="text-xs text-slate-400 mt-1">Horário de {{ form.tipo === 'saida' ? 'saída' : 'entrada' }}</p>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">
                Nº da Aula (opcional)
              </label>
              <select v-model="form.aula_numero" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-amber-500">
                <option :value="null">Não se aplica (horário livre)</option>
                <option v-for="n in 5" :key="n" :value="n">{{ n }}ª Aula</option>
              </select>
              <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                <Icon name="heroicons:information-circle-20-solid" class="w-3 h-3" />
                Informe apenas se for saída/entrada em aula específica
              </p>
            </div>
          </div>

          <div class="mt-4">
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">
              Motivo
            </label>
            <textarea v-model="form.motivo" rows="2" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-amber-500 resize-none" placeholder="Informe o motivo (opcional)"></textarea>
          </div>
        </div>
      </div>

      <!-- Configurações de Falta -->
      <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="p-5">
          <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-500/20 dark:to-amber-600/20 flex items-center justify-center">
              <Icon name="heroicons:exclamation-triangle-20-solid" class="w-4 h-4 text-amber-500" />
            </div>
            <h2 class="text-lg font-bold text-slate-800 dark:text-white">Configurações de Falta</h2>
          </div>

          <div class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
                Esta movimentação gera falta?
              </label>
              <div class="flex flex-wrap gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" value="true" v-model="form.com_falta" class="w-4 h-4 text-amber-500" />
                  <span class="text-sm">⚠️ Sim, gerar falta</span>
                  <span class="text-xs text-slate-400">(Aluno terá falta registrada)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" value="false" v-model="form.com_falta" class="w-4 h-4 text-green-500" />
                  <span class="text-sm">✅ Não gerar falta</span>
                  <span class="text-xs text-slate-400">(Saída/Entrada justificada)</span>
                </label>
              </div>
            </div>

            <div class="bg-amber-50 dark:bg-amber-500/10 rounded-lg p-3 border border-amber-200 dark:border-amber-500/20">
              <div class="flex items-start gap-2">
                <Icon name="heroicons:information-circle-20-solid" class="w-4 h-4 text-amber-500 mt-0.5" />
                <div class="text-xs text-amber-700 dark:text-amber-300">
                  <p class="font-medium mb-1">Como funciona o cálculo de faltas?</p>
                  <ul class="list-disc list-inside space-y-0.5">
                    <li v-if="form.tipo === 'saida'">Se sair na aula X, perderá as aulas a partir de X (inclusive)</li>
                    <li v-if="form.tipo === 'entrada'">Se chegar na aula X, perderá as aulas anteriores a X</li>
                    <li>Se não informar a aula, o sistema calculará automaticamente baseado no horário</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Notificação do Responsável -->
      <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="p-5">
          <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-500/20 dark:to-emerald-600/20 flex items-center justify-center">
                <Icon name="heroicons:envelope-20-solid" class="w-4 h-4 text-emerald-500" />
              </div>
              <h2 class="text-lg font-bold text-slate-800 dark:text-white">Notificação do Responsável</h2>
            </div>
            <span class="text-xs text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded-full">Opcional</span>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">
                E-mail do Responsável
              </label>
              <input v-model="form.responsavel_contato" type="email" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-emerald-500" placeholder="responsavel@email.com" />
              <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                <Icon name="heroicons:information-circle-20-solid" class="w-3 h-3" />
                O responsável receberá uma notificação quando a movimentação for registrada
              </p>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">
                Observações Adicionais
              </label>
              <input v-model="form.observacoes" type="text" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-emerald-500" placeholder="Informações adicionais" />
            </div>
          </div>
        </div>
      </div>

      <!-- Pré-visualização -->
      <div v-if="showPreview" class="bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="p-4">
          <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-red-500 flex items-center justify-center">
              <Icon name="heroicons:eye-20-solid" class="w-4 h-4 text-white" />
            </div>
            <h2 class="text-base font-bold text-slate-800 dark:text-white">Pré-visualização</h2>
          </div>
          
          <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
            <div>
              <p class="text-slate-400 text-xs">Tipo</p>
              <p class="font-medium capitalize">{{ form.tipo === 'saida' ? 'Saída' : 'Entrada' }}</p>
            </div>
            <div>
              <p class="text-slate-400 text-xs">Aluno</p>
              <p class="font-medium truncate">{{ form.aluno_nome || '-' }}</p>
            </div>
            <div>
              <p class="text-slate-400 text-xs">Turma</p>
              <p class="font-medium">{{ form.turma || '-' }}</p>
            </div>
            <div>
              <p class="text-slate-400 text-xs">Horário</p>
              <p class="font-medium">{{ form.horario || '-' }}</p>
            </div>
          </div>
          
          <div class="mt-3 pt-2 border-t border-slate-200 dark:border-slate-700">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
              <div>
                <p class="text-slate-400 text-xs">Turno</p>
                <p class="font-medium capitalize">{{ form.turno || '-' }}</p>
              </div>
              <div>
                <p class="text-slate-400 text-xs">Professor</p>
                <p class="font-medium text-sm">{{ form.professor_nome || '-' }}</p>
              </div>
              <div>
                <p class="text-slate-400 text-xs">Aula</p>
                <p class="font-medium">{{ form.aula_numero ? form.aula_numero + 'ª' : 'Horário livre' }}</p>
              </div>
              <div>
                <p class="text-slate-400 text-xs">Falta</p>
                <p class="font-medium" :class="form.com_falta === 'true' ? 'text-amber-600' : 'text-green-600'">
                  {{ form.com_falta === 'true' ? '⚠️ Sim' : '✅ Não' }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Botões -->
      <div class="flex justify-end gap-3 pt-4">
        <button @click="showPreview = !showPreview" type="button" class="px-5 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center gap-2">
          <Icon :name="showPreview ? 'heroicons:eye-slash-20-solid' : 'heroicons:eye-20-solid'" class="w-4 h-4" />
          {{ showPreview ? 'Ocultar' : 'Prévia' }}
        </button>
        
        <NuxtLink to="/admin/authorizations">
          <button type="button" class="px-5 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            Cancelar
          </button>
        </NuxtLink>
        
        <button type="submit" :disabled="submitting" class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors flex items-center gap-2 font-medium disabled:opacity-50 disabled:cursor-not-allowed shadow-sm">
          <Icon v-if="submitting" name="heroicons:arrow-path-20-solid" class="w-4 h-4 animate-spin" />
          <Icon v-else name="heroicons:paper-airplane-20-solid" class="w-4 h-4" />
          {{ submitting ? 'Enviando...' : 'Enviar Autorização' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useApi } from '~/composables/useApi'
import { useToast } from '~/composables/useToast'

definePageMeta({
  middleware: 'auth',
  layout: 'admin'
})

const { get, post } = useApi()
const { show: toast } = useToast()

const form = ref({
  tipo: 'saida',
  aluno_nome: '',
  turma: '',
  turma_id: '',
  turno: 'manha',
  motivo: '',
  horario: '',
  aula_numero: null,
  professor_id: '',
  professor_nome: '',
  com_falta: 'false',
  responsavel_contato: '',
  observacoes: ''
})

const turmas = ref([])
const professores = ref([])
const loadingTurmas = ref(false)
const loadingProfessores = ref(false)
const submitting = ref(false)
const showPreview = ref(false)

let turmaSearchTimeout = null
let professorSearchTimeout = null

const loadTurmas = async (search = '') => {
  loadingTurmas.value = true
  try {
    const params = search ? { search } : {}
    const response = await get('/turmas/list', params)
    turmas.value = response || []
  } finally {
    loadingTurmas.value = false
  }
}

const loadProfessores = async (search = '') => {
  loadingProfessores.value = true
  try {
    const params = search ? { search } : {}
    const response = await get('/usuarios', { ...params, role: 'professor' })
    professores.value = response || []
  } finally {
    loadingProfessores.value = false
  }
}

const handleTurmaSearch = (event) => {
  const searchTerm = event.target.value
  if (turmaSearchTimeout) clearTimeout(turmaSearchTimeout)
  turmaSearchTimeout = setTimeout(async () => {
    if (searchTerm.length > 1) await loadTurmas(searchTerm)
    else if (searchTerm.length === 0) await loadTurmas()
  }, 300)
  
  const selectedTurma = turmas.value.find(t => t.nome === searchTerm)
  if (selectedTurma) {
    form.value.turma_id = selectedTurma.id
    form.value.turma = selectedTurma.nome
  } else {
    form.value.turma_id = ''
    form.value.turma = searchTerm
  }
}

const handleProfessorSearch = (event) => {
  const searchTerm = event.target.value
  if (professorSearchTimeout) clearTimeout(professorSearchTimeout)
  professorSearchTimeout = setTimeout(async () => {
    if (searchTerm.length > 1) await loadProfessores(searchTerm)
    else if (searchTerm.length === 0) await loadProfessores()
  }, 300)
  
  const selectedProfessor = professores.value.find(p => p.name === searchTerm)
  if (selectedProfessor) {
    form.value.professor_id = selectedProfessor.id
    form.value.professor_nome = selectedProfessor.name
  } else {
    form.value.professor_id = ''
    form.value.professor_nome = searchTerm
  }
}

const submitForm = async () => {
  if (!form.value.aluno_nome) return toast('Digite o nome do aluno', 'warning')
  if (!form.value.turma) return toast('Digite o nome da turma', 'warning')
  if (!form.value.horario) return toast('Selecione o horário', 'warning')
  if (!form.value.professor_id && !form.value.professor_nome) return toast('Selecione um professor responsável', 'warning')
  
  submitting.value = true
  try {
    const payload = {
      aluno_nome: form.value.aluno_nome,
      turma: form.value.turma,
      turno: form.value.turno,
      motivo_saida: form.value.motivo,
      horario_saida: form.value.horario,
      aula_numero: form.value.aula_numero,
      professor_id: form.value.professor_id,
      tipo: form.value.tipo,
      com_falta: form.value.com_falta === 'true',
      observacoes: form.value.observacoes
    }
    
    if (form.value.responsavel_contato) payload.responsavel_contato = form.value.responsavel_contato
    
    await post('/authorizations', payload)
    toast('Autorização criada com sucesso!', 'success')
    
    form.value = {
      tipo: 'saida',
      aluno_nome: '',
      turma: '',
      turma_id: '',
      turno: 'manha',
      motivo: '',
      horario: '',
      aula_numero: null,
      professor_id: '',
      professor_nome: '',
      com_falta: 'false',
      responsavel_contato: '',
      observacoes: ''
    }
    showPreview.value = false
    await Promise.all([loadTurmas(), loadProfessores()])
  } catch (error) {
    toast(error.response?.data?.message || error.message || 'Erro ao criar autorização', 'error')
  } finally {
    submitting.value = false
  }
}

onMounted(async () => {
  await Promise.all([loadTurmas(), loadProfessores()])
})
</script>