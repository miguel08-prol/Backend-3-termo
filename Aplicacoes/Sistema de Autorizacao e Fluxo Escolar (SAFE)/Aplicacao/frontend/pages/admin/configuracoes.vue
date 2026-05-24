<template>
  <NuxtLayout name="admin">
    <div class="space-y-8">
      <!-- Header -->
      <header>
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg">
            <Icon name="heroicons:cog-8-tooth-20-solid" class="w-6 h-6 text-white" />
          </div>
          <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Configurações</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">Gerencie suas preferências e personalize o sistema</p>
          </div>
        </div>
      </header>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Coluna Principal - Formulários -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Perfil do Usuário -->
          <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-gradient-to-r from-slate-50 to-white dark:from-slate-800/50 dark:to-slate-800">
              <div class="flex items-center gap-3">
                <Icon name="heroicons:user-circle-20-solid" class="w-6 h-6 text-red-500" />
                <h2 class="text-xl font-bold text-slate-800 dark:text-white">Meu Perfil</h2>
              </div>
              <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Atualize suas informações pessoais</p>
            </div>
            <div class="p-6">
              <form @submit.prevent="salvarPerfil" class="space-y-5">
                <div>
                  <label class="text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-2">
                    Nome Completo
                  </label>
                  <input 
                    v-model="perfil.nome" 
                    type="text" 
                    class="form-input" 
                    placeholder="Seu nome completo"
                  >
                </div>
                <div>
                  <label class="text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-2">
                    E-mail
                  </label>
                  <input 
                    v-model="perfil.email" 
                    type="email" 
                    class="form-input" 
                    placeholder="seu@email.com"
                  >
                </div>
                <div>
                  <label class="text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-2">
                    Departamento / Setor
                  </label>
                  <input 
                    v-model="perfil.departamento" 
                    type="text" 
                    class="form-input" 
                    placeholder="Ex: Manutenção, Engenharia, TI"
                  >
                </div>
                <div class="flex justify-end">
                  <AppButton type="submit" variant="primary" :loading="salvandoPerfil">
                    <Icon name="heroicons:check-20-solid" class="w-4 h-4" />
                    Salvar Alterações
                  </AppButton>
                </div>
              </form>
            </div>
          </div>

          <!-- Alterar Senha -->
          <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-gradient-to-r from-slate-50 to-white dark:from-slate-800/50 dark:to-slate-800">
              <div class="flex items-center gap-3">
                <Icon name="heroicons:key-20-solid" class="w-6 h-6 text-red-500" />
                <h2 class="text-xl font-bold text-slate-800 dark:text-white">Segurança</h2>
              </div>
              <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Altere sua senha de acesso</p>
            </div>
            <div class="p-6">
              <form @submit.prevent="alterarSenha" class="space-y-5">
                <div>
                  <label class="text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-2">
                    Senha Atual
                  </label>
                  <div class="relative">
                    <input 
                      v-model="senha.senha_atual" 
                      :type="showSenhaAtual ? 'text' : 'password'" 
                      class="form-input pr-12" 
                      placeholder="Digite sua senha atual"
                    >
                    <button 
                      type="button"
                      @click="showSenhaAtual = !showSenhaAtual"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500 transition-colors"
                    >
                      <Icon v-if="showSenhaAtual" name="heroicons:eye-20-solid" class="w-5 h-5" />
                      <Icon v-else name="heroicons:eye-slash-20-solid" class="w-5 h-5" />
                    </button>
                  </div>
                </div>
                <div>
                  <label class="text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-2">
                    Nova Senha
                  </label>
                  <div class="relative">
                    <input 
                      v-model="senha.nova_senha" 
                      :type="showNovaSenha ? 'text' : 'password'" 
                      class="form-input pr-12" 
                      placeholder="Mínimo 6 caracteres"
                    >
                    <button 
                      type="button"
                      @click="showNovaSenha = !showNovaSenha"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500 transition-colors"
                    >
                      <Icon v-if="showNovaSenha" name="heroicons:eye-20-solid" class="w-5 h-5" />
                      <Icon v-else name="heroicons:eye-slash-20-solid" class="w-5 h-5" />
                    </button>
                  </div>
                </div>
                <div>
                  <label class="text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-2">
                    Confirmar Nova Senha
                  </label>
                  <div class="relative">
                    <input 
                      v-model="senha.nova_senha_confirmation" 
                      :type="showConfirmarSenha ? 'text' : 'password'" 
                      class="form-input pr-12" 
                      placeholder="Confirme a nova senha"
                    >
                    <button 
                      type="button"
                      @click="showConfirmarSenha = !showConfirmarSenha"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500 transition-colors"
                    >
                      <Icon v-if="showConfirmarSenha" name="heroicons:eye-20-solid" class="w-5 h-5" />
                      <Icon v-else name="heroicons:eye-slash-20-solid" class="w-5 h-5" />
                    </button>
                  </div>
                </div>
                <div class="flex justify-end">
                  <AppButton type="submit" variant="primary" :loading="salvandoSenha">
                    <Icon name="heroicons:check-20-solid" class="w-4 h-4" />
                    Alterar Senha
                  </AppButton>
                </div>
              </form>
            </div>
          </div>

          <!-- Preferências de Notificação -->
          <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-gradient-to-r from-slate-50 to-white dark:from-slate-800/50 dark:to-slate-800">
              <div class="flex items-center gap-3">
                <Icon name="heroicons:bell-20-solid" class="w-6 h-6 text-red-500" />
                <h2 class="text-xl font-bold text-slate-800 dark:text-white">Notificações</h2>
              </div>
              <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Configure como deseja receber alertas</p>
            </div>
            <div class="p-6 space-y-4">
              <div class="flex items-center justify-between py-2">
                <div>
                  <p class="font-bold text-slate-700 dark:text-slate-300">Notificações por E-mail</p>
                  <p class="text-xs text-slate-500">Receba alertas sobre novas OS e atualizações</p>
                </div>
                <button 
                  @click="notificacoes.email = !notificacoes.email"
                  :class="[
                    'relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200',
                    notificacoes.email ? 'bg-red-500' : 'bg-slate-300 dark:bg-slate-600'
                  ]"
                >
                  <span 
                    :class="[
                      'inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200',
                      notificacoes.email ? 'translate-x-6' : 'translate-x-1'
                    ]"
                  />
                </button>
              </div>
              <div class="flex items-center justify-between py-2">
                <div>
                  <p class="font-bold text-slate-700 dark:text-slate-300">Notificações Push</p>
                  <p class="text-xs text-slate-500">Alertas em tempo real no navegador</p>
                </div>
                <button 
                  @click="notificacoes.push = !notificacoes.push"
                  :class="[
                    'relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200',
                    notificacoes.push ? 'bg-red-500' : 'bg-slate-300 dark:bg-slate-600'
                  ]"
                >
                  <span 
                    :class="[
                      'inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200',
                      notificacoes.push ? 'translate-x-6' : 'translate-x-1'
                    ]"
                  />
                </button>
              </div>
              <div class="flex justify-end pt-4">
                <AppButton @click="salvarNotificacoes" variant="secondary" size="sm">
                  <Icon name="heroicons:check-20-solid" class="w-4 h-4" />
                  Salvar Preferências
                </AppButton>
              </div>
            </div>
          </div>
        </div>

        <!-- Coluna Lateral - Informações e Cards -->
        <div class="space-y-6">
          <!-- Card de Informação do Sistema -->
          <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-3xl p-6 text-white shadow-lg">
            <Icon name="heroicons:sparkles-solid" class="w-8 h-8 mb-4 opacity-90" />
            <h3 class="text-xl font-bold mb-2">MaintSys 4.0</h3>
            <p class="text-sm opacity-90 mb-4">Sistema de Manutenção Industrial</p>
            <div class="space-y-2 text-xs opacity-80">
              <div class="flex justify-between">
                <span>Versão</span>
                <span class="font-mono">1.2.0</span>
              </div>
              <div class="flex justify-between">
                <span>Ambiente</span>
                <span>Produção</span>
              </div>
              <div class="flex justify-between">
                <span>Última atualização</span>
                <span>{{ new Date().toLocaleDateString('pt-BR') }}</span>
              </div>
            </div>
          </div>

          <!-- Aparência (Tema) -->
          <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-4">
              <Icon name="heroicons:paint-brush-20-solid" class="w-5 h-5 text-red-500" />
              <h3 class="font-bold text-slate-800 dark:text-white">Aparência</h3>
            </div>
            <div class="space-y-4">
              <div class="grid grid-cols-3 gap-2">
                <button 
                  @click="colorMode.preference = 'light'"
                  :class="[
                    'p-3 rounded-xl border-2 transition-all text-center',
                    colorMode.value === 'light' 
                      ? 'border-red-500 bg-red-50 dark:bg-red-500/10' 
                      : 'border-slate-200 dark:border-slate-700 hover:border-red-300'
                  ]"
                >
                  <Icon name="heroicons:sun-20-solid" class="w-6 h-6 mx-auto mb-1 text-amber-500" />
                  <p class="text-xs font-bold text-slate-700 dark:text-slate-300">Claro</p>
                </button>
                <button 
                  @click="colorMode.preference = 'dark'"
                  :class="[
                    'p-3 rounded-xl border-2 transition-all text-center',
                    colorMode.value === 'dark' 
                      ? 'border-red-500 bg-red-50 dark:bg-red-500/10' 
                      : 'border-slate-200 dark:border-slate-700 hover:border-red-300'
                  ]"
                >
                  <Icon name="heroicons:moon-20-solid" class="w-6 h-6 mx-auto mb-1 text-indigo-500" />
                  <p class="text-xs font-bold text-slate-700 dark:text-slate-300">Escuro</p>
                </button>
                <button 
                  @click="colorMode.preference = 'system'"
                  :class="[
                    'p-3 rounded-xl border-2 transition-all text-center',
                    colorMode.value === 'system' 
                      ? 'border-red-500 bg-red-50 dark:bg-red-500/10' 
                      : 'border-slate-200 dark:border-slate-700 hover:border-red-300'
                  ]"
                >
                  <Icon name="heroicons:computer-desktop-20-solid" class="w-6 h-6 mx-auto mb-1 text-slate-500" />
                  <p class="text-xs font-bold text-slate-700 dark:text-slate-300">Sistema</p>
                </button>
              </div>
              <div class="pt-3 text-center">
                <p class="text-xs text-slate-500">{{ temaAtual }}</p>
              </div>
            </div>
          </div>

          <!-- Sobre / Suporte -->
          <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-4">
              <Icon name="heroicons:information-circle-20-solid" class="w-5 h-5 text-red-500" />
              <h3 class="font-bold text-slate-800 dark:text-white">Suporte</h3>
            </div>
            <div class="space-y-3">
              <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                Precisa de ajuda? Entre em contato com o suporte técnico.
              </p>
              <div class="flex items-center gap-2 text-sm text-slate-500">
                <Icon name="heroicons:envelope-20-solid" class="w-4 h-4" />
                <a href="mailto:suporte@maintsys.com" class="hover:text-red-500 transition-colors">suporte@maintsys.com</a>
              </div>
              <div class="flex items-center gap-2 text-sm text-slate-500">
                <Icon name="heroicons:phone-20-solid" class="w-4 h-4" />
                <span>(11) 4000-0000</span>
              </div>
              <button @click="abrirDocumentacao" class="mt-3 w-full py-2 bg-slate-100 dark:bg-slate-700 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 hover:bg-red-100 dark:hover:bg-red-500/20 hover:text-red-600 transition-all">
                <Icon name="heroicons:document-text-20-solid" class="w-4 h-4 inline mr-2" />
                Documentação
              </button>
            </div>
          </div>

          <!-- Dados e Backup -->
          <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-4">
              <Icon name="heroicons:archive-box-20-solid" class="w-5 h-5 text-red-500" />
              <h3 class="font-bold text-slate-800 dark:text-white">Dados</h3>
            </div>
            <div class="space-y-3">
              <button @click="exportarDados" class="w-full flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700/50 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 transition-all group">
                <span class="text-sm font-medium text-slate-600 dark:text-slate-300">Exportar todos os dados</span>
                <Icon name="heroicons:arrow-down-tray-20-solid" class="w-5 h-5 text-slate-400 group-hover:text-red-500 transition-colors" />
              </button>
              <button @click="limparCache" class="w-full flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700/50 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 transition-all group">
                <span class="text-sm font-medium text-slate-600 dark:text-slate-300">Limpar cache local</span>
                <Icon name="heroicons:trash-20-solid" class="w-5 h-5 text-slate-400 group-hover:text-red-500 transition-colors" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </NuxtLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useToast } from '~/composables/useToast'
import { useApi } from '~/composables/useApi'
import AppButton from '~/components/common/AppButton.vue'

definePageMeta({ middleware: 'auth' })

const { show: toast } = useToast()
const { get, post } = useApi()

// Tema
const colorMode = useColorMode()

const temaAtual = computed(() => {
  const tema = colorMode.value
  if (tema === 'light') return 'Modo claro ativo'
  if (tema === 'dark') return 'Modo escuro ativo'
  return 'Seguindo as configurações do sistema'
})

// Perfil
const perfil = reactive({
  nome: '',
  email: '',
  departamento: ''
})

const salvandoPerfil = ref(false)

// Senha - CORRIGIDO: campos corretos
const senha = reactive({
  senha_atual: '',
  nova_senha: '',
  nova_senha_confirmation: ''
})

const salvandoSenha = ref(false)
const showSenhaAtual = ref(false)
const showNovaSenha = ref(false)
const showConfirmarSenha = ref(false)

// Notificações
const notificacoes = reactive({
  email: true,
  push: false
})

// Carregar dados do usuário
const carregarUsuario = async () => {
  try {
    const user = await get('/user')
    perfil.nome = user.name || ''
    perfil.email = user.email || ''
    perfil.departamento = user.departamento || ''
    notificacoes.email = user.notificacoes_email ?? true
    notificacoes.push = user.notificacoes_push ?? false
  } catch (error) {
    console.error('Erro ao carregar usuário:', error)
  }
}

// Salvar perfil
const salvarPerfil = async () => {
  salvandoPerfil.value = true
  try {
    await post('/user/perfil', {
      name: perfil.nome,
      email: perfil.email,
      departamento: perfil.departamento
    })
    toast('Perfil atualizado com sucesso!')
  } catch (error) {
    toast(error.data?.message || 'Erro ao salvar perfil', 'error')
  } finally {
    salvandoPerfil.value = false
  }
}

// Alterar senha - CORRIGIDO
const alterarSenha = async () => {
  if (!senha.senha_atual) {
    toast('Digite sua senha atual', 'error')
    return
  }
  if (!senha.nova_senha || senha.nova_senha.length < 6) {
    toast('A nova senha deve ter pelo menos 6 caracteres', 'error')
    return
  }
  if (senha.nova_senha !== senha.nova_senha_confirmation) {
    toast('As senhas não coincidem', 'error')
    return
  }
  
  salvandoSenha.value = true
  try {
    await post('/user/alterar-senha', {
      senha_atual: senha.senha_atual,
      nova_senha: senha.nova_senha,
      nova_senha_confirmation: senha.nova_senha_confirmation
    })
    toast('Senha alterada com sucesso!')
    // Limpar formulário
    senha.senha_atual = ''
    senha.nova_senha = ''
    senha.nova_senha_confirmation = ''
  } catch (error) {
    toast(error.data?.message || 'Erro ao alterar senha', 'error')
  } finally {
    salvandoSenha.value = false
  }
}

// Salvar preferências de notificação
const salvarNotificacoes = async () => {
  try {
    await post('/user/notificacoes', {
      email: notificacoes.email,
      push: notificacoes.push
    })
    toast('Preferências salvas!')
  } catch (error) {
    toast('Erro ao salvar preferências', 'error')
  }
}

// Exportar dados
const exportarDados = () => {
  toast('Funcionalidade em desenvolvimento', 'info')
}

// Limpar cache
const limparCache = () => {
  localStorage.clear()
  sessionStorage.clear()
  toast('Cache limpo! Recarregue a página para aplicar.', 'success')
  setTimeout(() => {
    window.location.reload()
  }, 1500)
}

// Documentação
const abrirDocumentacao = () => {
  window.open('https://docs.maintsys.com', '_blank')
  toast('Abrindo documentação...', 'info')
}

onMounted(() => {
  carregarUsuario()
})
</script>

<style scoped>
.form-input {
  @apply w-full px-5 py-4 bg-slate-50 dark:bg-slate-800 border-2 border-transparent focus:border-red-500 focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all duration-200 outline-none font-semibold text-slate-700 dark:text-slate-300;
}

.form-input::placeholder {
  @apply text-slate-400 dark:text-slate-500;
}

/* Animações suaves */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.bg-gradient-to-br {
  animation: fadeIn 0.4s ease-out;
}
</style>