import { ref } from 'vue'
import { useApi } from './useApi'

export const useGateway = () => {
    const { get, post } = useApi()
    const pendingExits = ref([])
    const history = ref([])
    const loading = ref(false)
    const pagination = ref({
        current_page: 1,
        last_page: 1,
        total: 0,
        per_page: 20
    })

    // Buscar saídas pendentes
    const fetchPendingExits = async () => {
        loading.value = true
        try {
            const response = await get('/gateway/pending')
            pendingExits.value = response
            return response
        } finally {
            loading.value = false
        }
    }

    // Buscar histórico
    const fetchHistory = async (params = {}) => {
        loading.value = true
        try {
            const response = await get('/gateway/history', params)
            if (response.data) {
                history.value = response.data
                if (response.meta) pagination.value = response.meta
            } else {
                history.value = response
            }
            return history.value
        } finally {
            loading.value = false
        }
    }

    // Registrar saída
    const registerExit = async (id, observacoes = null) => {
        return await post(`/gateway/${id}/exit`, { observacoes })
    }

    // Registrar entrada
    const registerEntry = async (id, observacoes = null) => {
        return await post(`/gateway/${id}/entry`, { observacoes })
    }

    // Buscar por nome/turma
    const search = async (searchTerm) => {
        loading.value = true
        try {
            const response = await get('/gateway/search', { search: searchTerm })
            return response
        } finally {
            loading.value = false
        }
    }

    return {
        pendingExits,
        history,
        loading,
        pagination,
        fetchPendingExits,
        fetchHistory,
        registerExit,
        registerEntry,
        search
    }
}