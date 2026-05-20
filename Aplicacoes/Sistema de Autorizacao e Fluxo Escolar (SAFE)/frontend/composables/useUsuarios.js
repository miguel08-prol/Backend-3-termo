import { ref } from 'vue'
import { useApi } from './useApi'

export const useUsuarios = () => {
  const usuarios = ref([])
  const loading = ref(false)
  const { get, post, put, del } = useApi()

  const fetchByRole = async (role) => {
    loading.value = true
    try {
      const data = await get(`/usuarios?role=${role}`)
      usuarios.value = data
      return data
    } catch (error) {
      console.error('Erro ao buscar usuários:', error)
      throw error
    } finally {
      loading.value = false
    }
  }

  const create = async (data) => {
    const response = await post('/usuarios', data)
    return response
  }

  const update = async (id, data) => {
    const response = await put(`/usuarios/${id}`, data)
    return response
  }

  const remove = async (id) => {
    const response = await del(`/usuarios/${id}`)
    return response
  }

  return {
    usuarios,
    loading,
    fetchByRole,
    create,
    update,
    remove
  }
}