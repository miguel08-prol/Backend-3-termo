<template>
  <div>
    <NuxtLink 
      to="/" 
      class="inline-flex items-center text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200 mb-8 group"
    >
      <Icon name="heroicons:arrow-left-20-solid" class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform duration-200" />
      Voltar ao Início
    </NuxtLink>

    <div class="mb-8">
      <div class="flex items-center gap-3 mb-2">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg">
          <Icon name="heroicons:key-20-solid" class="w-5 h-5 text-white" />
        </div>
        <h2 class="text-3xl font-bold text-slate-900 dark:text-white">Acessar Sistema</h2>
      </div>
      <p class="text-sm text-slate-500 dark:text-slate-400">Insira suas credenciais corporativas para continuar.</p>
    </div>
    
    <form @submit.prevent="handleLogin" class="space-y-5">
      <div>
        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">E-mail institucional</label>
        <div class="relative">
          <Icon name="heroicons:envelope-20-solid" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
          <input 
            v-model="form.email"
            type="email" 
            placeholder="nome@senai.com.br"
            class="w-full pl-12 pr-4 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all shadow-sm"
            required
            :disabled="isLoading"
          />
        </div>
      </div>

      <div>
        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Senha</label>
        <div class="relative">
          <Icon name="heroicons:lock-closed-20-solid" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
          <input 
            v-model="form.password"
            :type="showPassword ? 'text' : 'password'" 
            placeholder="••••••••"
            class="w-full pl-12 pr-12 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all shadow-sm"
            required
            :disabled="isLoading"
          />
          <button 
            type="button"
            @click="togglePassword"
            class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 hover:text-red-500 transition-colors duration-200 focus:outline-none"
          >
            <Icon v-if="showPassword" name="heroicons:eye-20-solid" class="w-5 h-5" />
            <Icon v-else name="heroicons:eye-slash-20-solid" class="w-5 h-5" />
          </button>
        </div>
      </div>

      <!-- Opção extra: Lembrar-me e Esqueci senha -->
      <div class="flex items-center justify-between select-none">
        <label class="flex items-center gap-2 cursor-pointer text-sm text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
          <input 
            type="checkbox" 
            v-model="lembrar" 
            class="accent-red-500 h-4 w-4 rounded border-slate-300 dark:border-slate-600 focus:ring-red-500 cursor-pointer"
          >
          <span>Lembrar-me</span>
        </label>
        <a href="#" class="text-sm text-red-500 hover:text-red-600 font-medium transition-colors">Esqueceu a senha?</a>
      </div>

      <button 
        type="submit" 
        :disabled="isLoading"
        class="w-full flex justify-center items-center gap-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-70 disabled:cursor-not-allowed mt-2"
      >
        <Icon v-if="isLoading" name="heroicons:arrow-path-20-solid" class="w-5 h-5 animate-spin" />
        <Icon v-else name="heroicons:arrow-right-end-on-rectangle-20-solid" class="w-5 h-5" />
        {{ isLoading ? 'Autenticando...' : 'Entrar no Sistema' }}
      </button>
    </form>
    
    <!-- Mensagem de erro melhorada com transição -->
    <div v-if="error" class="mt-4 p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 rounded-xl flex items-start gap-3 animate-fadeIn">
      <Icon name="heroicons:exclamation-triangle-20-solid" class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" />
      <div>
        <p class="text-sm font-semibold text-red-700 dark:text-red-400">Erro ao autenticar</p>
        <p class="text-sm text-red-600 dark:text-red-300">{{ error }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'

const form = ref({ email: '', password: '' })
const error = ref('')
const isLoading = ref(false)
const showPassword = ref(false)
const lembrar = ref(false)

let errorTimeout = null

watch(error, (newError) => {
  if (errorTimeout) clearTimeout(errorTimeout)
  if (newError) {
    errorTimeout = setTimeout(() => {
      error.value = ''
    }, 5000)
  }
})

const togglePassword = () => {
  showPassword.value = !showPassword.value
}

const handleLogin = async () => {
  if (isLoading.value) return
  
  error.value = ''
  isLoading.value = true
  
  if (!form.value.email || !form.value.password) {
    error.value = 'Preencha todos os campos.'
    isLoading.value = false
    return
  }
  
  try {
    console.log('Tentando login com:', form.value.email)
    
    const response = await axios.post('http://127.0.0.1:8000/api/login', {
      email: form.value.email,
      password: form.value.password
    }, {
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      timeout: 10000
    })
    
    console.log('Resposta do login:', response.data)
    
    if (!response.data || !response.data.access_token) {
      throw new Error('Resposta inválida do servidor')
    }
    
    const token = response.data.access_token
    const user = response.data.user
    
    // SALVAR EM LOCALSTORAGE (OBRIGATÓRIO)
    localStorage.setItem('auth_token', token)
    localStorage.setItem('auth_user', JSON.stringify(user))
    localStorage.setItem('user_role', user?.role || 'admin')
    
    // Também salvar em cookie para garantir
    document.cookie = `auth_token=${token}; path=/; max-age=86400`
    document.cookie = `user_role=${user?.role || 'admin'}; path=/; max-age=86400`
    
    // Verificar se salvou corretamente
    console.log('Token salvo:', localStorage.getItem('auth_token'))
    console.log('Role salvo:', localStorage.getItem('user_role'))
    
    // Redirecionamento baseado no role
    const userRole = user?.role
    console.log('Redirecionando para:', userRole)
    
    switch (userRole) {
      case 'admin':
        await navigateTo('/admin/dashboard')
        break
      case 'professor':
        await navigateTo('/professor/dashboard')
        break
      case 'tecnico':
        await navigateTo('/gateway/dashboard')
        break
      default:
        await navigateTo('/dashboard')
    }
    
  } catch (err) {
    console.error('Erro detalhado no login:', err)
    
    if (err.code === 'ECONNABORTED') {
      error.value = 'Tempo limite excedido. Verifique sua conexão.'
    } else if (err.response) {
      const status = err.response.status
      const data = err.response.data
      
      if (status === 401) {
        error.value = data?.message || 'E-mail ou senha incorretos.'
      } else if (status === 422) {
        error.value = 'Dados inválidos. Verifique os campos.'
      } else if (status === 500) {
        error.value = 'Erro interno do servidor. Tente novamente mais tarde.'
      } else {
        error.value = data?.message || `Erro ${status}: Falha na autenticação.`
      }
    } else if (err.request) {
      error.value = 'Não foi possível conectar ao servidor. Verifique se o backend está rodando em http://127.0.0.1:8000'
    } else {
      error.value = err.message || 'Erro desconhecido. Tente novamente.'
    }
  } finally {
    isLoading.value = false
  }
}
</script>

<style scoped>
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.3s ease-out;
}
</style>