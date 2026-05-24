// composables/useTurmas.js
import { ref } from 'vue'
import { useApi } from './useApi'

export const useTurmas = () => {
    const { get, post, put, del } = useApi()
    
    const turmas = ref([])
    const turma = ref(null)
    const loading = ref(false)
    const pagination = ref({
        current_page: 1,
        last_page: 1,
        per_page: 20,
        total: 0
    })
    
    const fetchAll = async (params = {}) => {
        loading.value = true
        try {
            const response = await get('/turmas', params)
            turmas.value = response.data
            pagination.value = {
                current_page: response.current_page,
                last_page: response.last_page,
                per_page: response.per_page,
                total: response.total
            }
            return response
        } finally {
            loading.value = false
        }
    }
    
    const fetchList = async () => {
        loading.value = true
        try {
            const response = await get('/turmas/list')
            turmas.value = response
            return response
        } finally {
            loading.value = false
        }
    }
    
    const find = async (id) => {
        loading.value = true
        try {
            const response = await get(`/turmas/${id}`)
            turma.value = response
            return response
        } finally {
            loading.value = false
        }
    }
    
    const create = async (data) => {
        loading.value = true
        try {
            const response = await post('/turmas', data)
            return response
        } finally {
            loading.value = false
        }
    }
    
    const update = async (id, data) => {
        loading.value = true
        try {
            const response = await put(`/turmas/${id}`, data)
            return response
        } finally {
            loading.value = false
        }
    }
    
    const destroy = async (id) => {
        loading.value = true
        try {
            const response = await del(`/turmas/${id}`)
            return response
        } finally {
            loading.value = false
        }
    }
    
    return {
        turmas,
        turma,
        loading,
        pagination,
        fetchAll,
        fetchList,
        find,
        create,
        update,
        destroy
    }
}