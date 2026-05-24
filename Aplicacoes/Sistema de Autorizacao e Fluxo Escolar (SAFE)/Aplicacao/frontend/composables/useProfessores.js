// composables/useProfessores.js
import { ref } from 'vue'
import { useApi } from './useApi'

export const useProfessores = () => {
    const { get } = useApi()
    
    const professores = ref([])
    const loading = ref(false)
    
    const fetchAll = async () => {
        loading.value = true
        try {
            const response = await get('/professores/list')
            professores.value = response
            return response
        } finally {
            loading.value = false
        }
    }
    
    return {
        professores,
        loading,
        fetchAll
    }
}