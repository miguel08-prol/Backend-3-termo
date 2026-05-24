<template>
  <button
    @click="toggleTheme"
    class="relative w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all duration-300 flex items-center justify-center group"
    :aria-label="isDark ? 'Mudar para tema claro' : 'Mudar para tema escuro'"
  >
    <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-blue-500/20 to-purple-500/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
    
    <!-- Ícone do sol (modo claro) -->
    <Icon 
      v-if="!isDark"
      name="heroicons:sun-20-solid" 
      class="w-5 h-5 text-amber-500 animate-float"
    />
    
    <!-- Ícone da lua (modo escuro) -->
    <Icon 
      v-else
      name="heroicons:moon-20-solid" 
      class="w-5 h-5 text-indigo-400 animate-pulse"
    />
  </button>
</template>

<script setup>
const colorMode = useColorMode()

const isDark = computed(() => colorMode.value === 'dark')

const toggleTheme = () => {
  colorMode.preference = colorMode.value === 'dark' ? 'light' : 'dark'
}
</script>