// composables/useApi.js
export const useApi = () => {
  const config = useRuntimeConfig()
  
  const getToken = () => {
    if (process.client) {
      // Tenta pegar do localStorage primeiro
      let token = localStorage.getItem('auth_token')
      if (token) {
        console.log('Token encontrado no localStorage')
        return token
      }
      
      // Tenta pegar do cookie como fallback
      const cookies = document.cookie.split('; ')
      const authCookie = cookies.find(row => row.startsWith('auth_token='))
      if (authCookie) {
        token = decodeURIComponent(authCookie.split('=')[1])
        if (token) {
          console.log('Token encontrado no cookie, sincronizando com localStorage')
          localStorage.setItem('auth_token', token)
        }
        return token
      }
      
      console.log('Nenhum token encontrado')
    }
    return null
  }
  
  const getHeaders = () => {
    const headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    }
    
    const token = getToken()
    if (token) {
      headers['Authorization'] = `Bearer ${token}`
      console.log('Header Authorization adicionado:', headers['Authorization'].substring(0, 30) + '...')
    } else {
      console.warn('Sem token para adicionar ao header')
    }
    
    return headers
  }
  
  const clearAuth = () => {
    if (process.client) {
      console.log('Limpando autenticação')
      localStorage.removeItem('auth_token')
      localStorage.removeItem('auth_user')
      localStorage.removeItem('user_role')
      
      document.cookie = 'auth_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;'
      document.cookie = 'user_role=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;'
    }
  }
  
  const request = async (url, options = {}) => {
    try {
      const baseUrl = config.public.apiBase || 'http://127.0.0.1:8000/api'
      const fullUrl = url.startsWith('http') ? url : `${baseUrl}${url}`
      
      const headers = getHeaders()
      console.log('Fazendo requisição para:', fullUrl)
      console.log('Headers:', headers)
      
      const response = await fetch(fullUrl, {
        ...options,
        headers: {
          ...headers,
          ...options.headers
        }
      })
      
      console.log('Resposta status:', response.status)
      
      if (response.status === 401) {
        console.error('Erro 401 - Token inválido ou expirado')
        clearAuth()
        
        // Redirecionar para login se não estiver já lá
        if (process.client && window.location.pathname !== '/login') {
          console.log('Redirecionando para login...')
          window.location.href = '/login'
        }
        throw new Error('Sessão expirada. Faça login novamente.')
      }
      
      if (!response.ok) {
        const error = await response.json().catch(() => ({}))
        throw new Error(error.message || `Erro ${response.status}`)
      }
      
      const data = await response.json()
      console.log('Resposta recebida com sucesso')
      return data
      
    } catch (error) {
      console.error('API Error:', error)
      throw error
    }
  }
  
  return {
    get: (url, params = {}) => {
      const queryString = new URLSearchParams(params).toString()
      const finalUrl = queryString ? `${url}?${queryString}` : url
      return request(finalUrl, { method: 'GET' })
    },
    post: (url, data) => request(url, { 
      method: 'POST', 
      body: JSON.stringify(data) 
    }),
    put: (url, data) => request(url, { 
      method: 'PUT', 
      body: JSON.stringify(data) 
    }),
    del: (url) => request(url, { method: 'DELETE' }),
    clearAuth
  }
}