<!-- app.vue -->
<template>
  <div>
    <NuxtLayout>
      <NuxtPage />
    </NuxtLayout>
  </div>
</template>

<script setup>
import { useAuthStore } from '~/stores/auth'

const authStore = useAuthStore()

// Carregar auth ANTES de qualquer coisa
if (process.client) {
  authStore.loadAuth()
}
</script>

<style>
/* Reset básico ou fontes globais */
body {
  @apply bg-gray-50;
  font-family: 'Inter', sans-serif;
}

.dark {
  --text-primary: #f1f5f9;
  --text-secondary: #cbd5e1;
  --bg-primary: #0f172a;
  --bg-secondary: #1e293b;
}

/* Ajuste para textos em fundos escuros */
.dark .text-slate-600 { color: #94a3b8 !important; }
.dark .text-slate-700 { color: #cbd5e1 !important; }
.dark .text-slate-800 { color: #e2e8f0 !important; }

/* Sidebar vermelho no modo escuro mantém as cores originais */
.dark .bg-gradient-to-b.from-red-700 {
  background: linear-gradient(to bottom, #991b1b, #7f1d1d, #450a0a);
}
</style>