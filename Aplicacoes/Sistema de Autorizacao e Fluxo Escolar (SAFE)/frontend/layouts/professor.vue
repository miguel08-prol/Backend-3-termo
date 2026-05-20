<template>
  <div class="min-h-screen flex bg-slate-50 dark:bg-slate-950 font-sans">
    
    <!-- Header Mobile -->
    <header class="lg:hidden fixed top-0 left-0 right-0 h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 z-40 flex items-center justify-between px-4 shadow-sm">
      <div class="flex items-center gap-3">
        <button @click="isMobileMenuOpen = true" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" aria-label="Abrir menu">
          <Icon name="heroicons:bars-3-center-left-solid" class="w-6 h-6" />
        </button>
        <div class="font-bold text-slate-800 dark:text-white tracking-tight text-lg flex items-center gap-2">
          SAFE
          <span class="px-2 py-0.5 rounded-md bg-slate-500/10 text-slate-600 dark:text-slate-400 text-xs font-bold border border-slate-500/20">Professor</span>
        </div>
      </div>
    </header>

    <div 
      v-if="isMobileMenuOpen" 
      class="fixed inset-0 bg-slate-950/80 z-40 lg:hidden transition-opacity duration-300" 
      @click="isMobileMenuOpen = false"
      aria-hidden="true"
    ></div>

    <!-- Sidebar -->
    <aside :class="[
      'fixed lg:sticky top-0 h-screen flex flex-col transition-all duration-300 ease-in-out z-50 overflow-hidden shrink-0 group',
      'bg-slate-950 border-r border-slate-800/60 shadow-2xl shadow-black/50',
      isMobileMenuOpen ? 'translate-x-0 w-72' : '-translate-x-full w-72 lg:translate-x-0 lg:w-20 lg:hover:w-72'
    ]">
      
      <!-- Logo -->
      <div class="flex items-center justify-between h-20 px-4 shrink-0">
        <div class="flex items-center gap-3">
          <div class="min-w-[2.25rem] h-9 rounded-lg bg-gradient-to-br from-slate-500 to-slate-700 flex items-center justify-center font-bold text-white shadow-[0_0_15px_rgba(100,116,139,0.3)] border border-slate-400/20">
            S
          </div>
          <div :class="[
            'flex flex-col justify-center whitespace-nowrap transition-all duration-300',
            isMobileMenuOpen ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0'
          ]">
            <span class="text-white font-bold tracking-tight leading-none text-lg">SAFE</span>
            <span class="text-slate-400 text-[11px] font-bold tracking-widest uppercase mt-1">Professor</span>
          </div>
        </div>
      </div>

      <!-- Navegação -->
      <nav class="flex-1 overflow-y-auto overflow-x-hidden py-4 space-y-8 custom-scrollbar">
        
        <div class="px-3">
          <p :class="[
            'px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2 transition-opacity duration-300',
            isMobileMenuOpen ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'
          ]">
            Principal
          </p>
          <ul class="space-y-1">
            <li>
              <NuxtLink to="/professor/dashboard" class="nav-item" active-class="nav-active">
                <div class="nav-icon">
                  <Icon name="heroicons:home-20-solid" class="w-5 h-5" />
                </div>
                <span :class="['nav-text', isMobileMenuOpen ? 'opacity-100' : 'opacity-0 group-hover:opacity-100']">Dashboard</span>
              </NuxtLink>
            </li>
          </ul>
        </div>

        <div class="px-3">
          <p :class="[
            'px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2 transition-opacity duration-300',
            isMobileMenuOpen ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'
          ]">
            Autorizações
          </p>
          <ul class="space-y-1">
            <li>
              <NuxtLink to="/professor/authorizations" class="nav-item" active-class="nav-active">
                <div class="nav-icon">
                  <Icon name="heroicons:clock-20-solid" class="w-5 h-5" />
                </div>
                <span :class="['nav-text', isMobileMenuOpen ? 'opacity-100' : 'opacity-0 group-hover:opacity-100']">Pendentes</span>
              </NuxtLink>
            </li>
            <li>
              <NuxtLink to="/professor/authorizations/history" class="nav-item" active-class="nav-active">
                <div class="nav-icon">
                  <Icon name="heroicons:document-text-20-solid" class="w-5 h-5" />
                </div>
                <span :class="['nav-text', isMobileMenuOpen ? 'opacity-100' : 'opacity-0 group-hover:opacity-100']">Histórico</span>
              </NuxtLink>
            </li>
          </ul>
        </div>
      </nav>

      <!-- Footer -->
      <div class="p-3 border-t border-slate-800/60 space-y-1 shrink-0 bg-slate-950/50">
        <NuxtLink to="/professor/configuracoes" class="nav-item" active-class="nav-active">
          <div class="nav-icon">
            <Icon name="heroicons:cog-8-tooth-solid" class="w-5 h-5" />
          </div>
          <span :class="['nav-text', isMobileMenuOpen ? 'opacity-100' : 'opacity-0 group-hover:opacity-100']">Configurações</span>
        </NuxtLink>

        <button @click="logout" class="nav-item w-full group/logout">
          <div class="nav-icon text-slate-400 group-hover/logout:text-red-400 transition-colors">
            <Icon name="heroicons:arrow-right-start-on-rectangle-solid" class="w-5 h-5" />
          </div>
          <span :class="['nav-text group-hover/logout:text-red-400', isMobileMenuOpen ? 'opacity-100' : 'opacity-0 group-hover:opacity-100']">Sair do Sistema</span>
        </button>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-4 pt-20 lg:pt-8 lg:p-8 transition-all duration-300 min-w-0">
      <div class="max-w-7xl mx-auto">
        <slot />
      </div>
    </main>
    
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useRoute, navigateTo, useCookie } from '#imports'

const isMobileMenuOpen = ref(false)
const route = useRoute()

watch(() => route.fullPath, () => {
  isMobileMenuOpen.value = false
})

const logout = () => {
  const token = useCookie('auth_token')
  const userRole = useCookie('user_role')
  token.value = null
  userRole.value = null
  navigateTo('/login')
}
</script>

<style scoped>
.nav-item {
  @apply flex items-center h-[2.75rem] px-2 mx-2 rounded-lg text-slate-300 font-medium transition-all duration-200 cursor-pointer overflow-hidden border border-transparent;
}

.nav-item:hover:not(.nav-active) {
  @apply bg-slate-800/50 text-white;
}

.nav-icon {
  @apply min-w-[2.25rem] flex items-center justify-center text-slate-400 transition-colors duration-200;
}

.nav-item:hover:not(.nav-active) .nav-icon {
  @apply text-slate-200;
}

.nav-text {
  @apply whitespace-nowrap transition-all duration-300 delay-75 text-sm ml-1;
}

.nav-active {
  @apply bg-slate-500/10 text-white shadow-[inset_3px_0_0_0_#64748b];
}

.nav-active .nav-icon {
  @apply text-slate-400;
}

.nav-active .nav-text {
  @apply font-semibold;
}

.custom-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: rgba(255, 255, 255, 0.1) transparent;
}

.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: transparent;
  border-radius: 10px;
}

.custom-scrollbar:hover::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.15);
}
</style>