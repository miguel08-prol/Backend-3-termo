// composables/useApi.js
export const useApi = () => {
  const config = useRuntimeConfig()
  
  const getToken = () => {
    // Buscar token diretamente do localStorage
    if (process.client) {
      const token = localStorage.getItem('auth_token')
      if (token) {
        console.log('Token encontrado no localStorage:', token.substring(0, 30) + '...')
        return token
      }
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
    }
    
    return headers
  }
  
  const request = async (url, options = {}) => {
    try {
      const baseUrl = config.public.apiBase || 'http://127.0.0.1:8000/api'
      const fullUrl = url.startsWith('http') ? url : `${baseUrl}${url}`
      
      console.log('Requisição:', fullUrl)
      
      const response = await fetch(fullUrl, {
        ...options,
        headers: {
          ...getHeaders(),
          ...options.headers
        }
      })
      
      if (response.status === 401) {
        console.error('Erro 401 - Token inválido')
        // Não fazer logout automático, apenas limpar token
        if (process.client) {
          localStorage.removeItem('auth_token')
          localStorage.removeItem('auth_user')
        }
        throw new Error('Sessão expirada. Faça login novamente.')
      }
      
      if (!response.ok) {
        const error = await response.json().catch(() => ({}))
        throw new Error(error.message || `Erro ${response.status}`)
      }
      
      return await response.json()
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
    post: (url, data) => request(url, { method: 'POST', body: JSON.stringify(data) }),
    put: (url, data) => request(url, { method: 'PUT', body: JSON.stringify(data) }),
    del: (url) => request(url, { method: 'DELETE' })
  }
}