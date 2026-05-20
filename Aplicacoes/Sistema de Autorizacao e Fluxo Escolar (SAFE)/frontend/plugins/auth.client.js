// plugins/auth.client.js
import { useAuthStore } from '~/stores/auth'

export default defineNuxtPlugin(() => {
  const authStore = useAuthStore()
  authStore.loadAuth()
  
  // Adicionar interceptor para todas as requisições
  const { $fetch } = useNuxtApp()
  
  if ($fetch) {
    $fetch.onRequest(({ options }) => {
      const token = authStore.token
      if (token) {
        options.headers = {
          ...options.headers,
          Authorization: `Bearer ${token}`
        }
      }
    })
  }
})