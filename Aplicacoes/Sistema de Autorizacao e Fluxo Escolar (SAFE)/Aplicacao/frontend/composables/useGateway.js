// composables/useGateway.js
import { ref } from 'vue'
import { useApi } from './useApi'

export const useGateway = () => {
    const { get, post } = useApi()
    
    const pendingExits = ref([])
    const history = ref([])
    const stats = ref({
        pendentes: 0,
        hoje: 0,
        total_mes: 0,
        com_falta: 0
    })
    const loading = ref(false)
    const loadingHistory = ref(false)
    const pagination = ref({
        current_page: 1,
        last_page: 1,
        per_page: 20,
        total: 0
    })
    
    // Buscar saídas pendentes
    const fetchPendingExits = async () => {
        loading.value = true
        try {
            const response = await get('/gateway/pending')
            console.log('Saídas pendentes carregadas:', response)
            pendingExits.value = response || []
            return response
        } catch (error) {
            console.error('Erro ao buscar saídas pendentes:', error)
            pendingExits.value = []
            return []
        } finally {
            loading.value = false
        }
    }
    
    // Buscar estatísticas
    const fetchStats = async () => {
        try {
            const response = await get('/gateway/stats')
            console.log('Estatísticas carregadas:', response)
            stats.value = response
            return response
        } catch (error) {
            console.error('Erro ao buscar estatísticas:', error)
            return {}
        }
    }
    
    // Buscar histórico
    const fetchHistory = async (params = {}) => {
        loadingHistory.value = true
        try {
            const response = await get('/gateway/history', params)
            console.log('Histórico carregado:', response)
            history.value = response.data || response
            pagination.value = {
                current_page: response.current_page || 1,
                last_page: response.last_page || 1,
                per_page: response.per_page || 20,
                total: response.total || 0
            }
            return response
        } catch (error) {
            console.error('Erro ao buscar histórico:', error)
            history.value = []
            return []
        } finally {
            loadingHistory.value = false
        }
    }
    
    // Buscar por nome
    const search = async (query) => {
        try {
            const response = await get('/gateway/search', { search: query })
            console.log('Resultado da busca:', response)
            return response
        } catch (error) {
            console.error('Erro ao buscar:', error)
            return []
        }
    }
    
    // Registrar saída
    const registerExit = async (authorizationId, observacoes = '') => {
        console.log('Registrando saída para autorização:', authorizationId)
        const response = await post(`/gateway/${authorizationId}/exit`, { observacoes })
        console.log('Resposta do registro:', response)
        return response
    }
    
    return {
        pendingExits,
        history,
        stats,
        loading,
        loadingHistory,
        pagination,
        fetchPendingExits,
        fetchStats,
        fetchHistory,
        search,
        registerExit
    }
}