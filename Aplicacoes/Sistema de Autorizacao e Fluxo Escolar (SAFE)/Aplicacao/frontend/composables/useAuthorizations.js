import { ref } from 'vue'
import { useApi } from './useApi'

export const useAuthorizations = () => {
    const { get, post, put, del } = useApi()
    const authorizations = ref([])
    const pendingAuthorizations = ref([])
    const loading = ref(false)
    const pagination = ref({
        current_page: 1,
        last_page: 1,
        total: 0,
        per_page: 20
    })

    // Admin: Listar todas autorizações
    const fetchAll = async (params = {}) => {
        loading.value = true
        try {
            const response = await get('/authorizations', params)
            if (response.data) {
                authorizations.value = response.data
                if (response.meta) pagination.value = response.meta
            } else {
                authorizations.value = response
            }
            return authorizations.value
        } finally {
            loading.value = false
        }
    }

    // Admin: Criar autorização
    const create = async (data) => {
        return await post('/authorizations', data)
    }

    // Admin: Atualizar autorização
// composables/useAuthorizations.js
const update = async (id, data) => {
    console.log('Atualizando autorização:', id, data)
    try {
        const response = await put(`/authorizations/${id}`, data)
        console.log('Resposta do update:', response)
        return response
    } catch (error) {
        console.error('Erro no update:', error)
        throw error
    }
}

    // Admin: Cancelar autorização
    const cancel = async (id) => {
        return await del(`/authorizations/${id}`)
    }

    // Professor: Buscar pendentes
    const fetchPending = async () => {
        loading.value = true
        try {
            const response = await get('/professor/authorizations/pending')
            pendingAuthorizations.value = response
            return response
        } finally {
            loading.value = false
        }
    }

    // Professor: Buscar histórico
    const fetchHistory = async (page = 1) => {
        loading.value = true
        try {
            const response = await get('/professor/authorizations/history', { page })
            if (response.data) {
                authorizations.value = response.data
                if (response.meta) pagination.value = response.meta
            } else {
                authorizations.value = response
            }
            return authorizations.value
        } finally {
            loading.value = false
        }
    }

    // Professor: Aprovar autorização
    const approve = async (id, comFalta, observacao = null) => {
        return await post(`/professor/authorizations/${id}/approve`, {
            com_falta: comFalta,
            observacao
        })
    }

    // Professor: Rejeitar autorização
    const reject = async (id, observacao) => {
        return await post(`/professor/authorizations/${id}/reject`, { observacao })
    }

    // Professor: Estatísticas
    const getStats = async () => {
        return await get('/professor/authorizations/stats')
    }

    // Buscar uma autorização específica
    const findById = async (id, role = 'professor') => {
        const prefix = role === 'admin' ? '/authorizations' : '/professor/authorizations'
        return await get(`${prefix}/${id}`)
    }

    // Buscar professores disponíveis
    const fetchProfessores = async () => {
        return await get('/authorizations/professores')
    }

    

    return {
        authorizations,
        pendingAuthorizations,
        loading,
        pagination,
        fetchAll,
        create,
        update,
        cancel,
        fetchPending,
        fetchHistory,
        approve,
        reject,
        update,
        getStats,
        findById,
        fetchProfessores
    }
}