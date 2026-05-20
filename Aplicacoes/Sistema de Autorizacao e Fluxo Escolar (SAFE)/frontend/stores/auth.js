// stores/auth.js
import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null
  }),
  
  getters: {
    isAuthenticated: (state) => !!state.token,
    isAdmin: (state) => state.user?.role === 'admin',
    isProfessor: (state) => state.user?.role === 'professor',
    isTecnico: (state) => state.user?.role === 'tecnico'
  },
  
  actions: {
    setAuth(data) {
      console.log('Setting auth:', data)
      this.user = data.user
      this.token = data.access_token
      
      if (process.client) {
        localStorage.setItem('auth_token', this.token)
        localStorage.setItem('auth_user', JSON.stringify(this.user))
      }
      
      // Configurar o token para as próximas requisições
      if (typeof $fetch !== 'undefined') {
        $fetch.setHeader('Authorization', `Bearer ${this.token}`)
      }
    },
    
    loadAuth() {
      if (process.client) {
        const token = localStorage.getItem('auth_token')
        const user = localStorage.getItem('auth_user')
        
        console.log('Loading auth from localStorage:', { token: !!token, user: !!user })
        
        if (token && user) {
          this.token = token
          this.user = JSON.parse(user)
          
          // Configurar o token para as requisições
          if (typeof $fetch !== 'undefined') {
            $fetch.setHeader('Authorization', `Bearer ${this.token}`)
          }
        }
      }
    },
    
    logout() {
      console.log('Logging out')
      this.user = null
      this.token = null
      
      if (process.client) {
        localStorage.removeItem('auth_token')
        localStorage.removeItem('auth_user')
      }
      
      navigateTo('/login')
    }
  }
})