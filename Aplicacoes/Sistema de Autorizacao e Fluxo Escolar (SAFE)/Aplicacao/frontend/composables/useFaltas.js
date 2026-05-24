import { ref } from 'vue'
import { useApi } from './useApi'

export const useFaltas = () => {
    const { get, post } = useApi()
    
    const faltas = ref([])
    const resumoFaltas = ref(null)
    const loading = ref(false)
    
    // Buscar faltas de um aluno
    const fetchFaltasByAluno = async (alunoId, params = {}) => {
        loading.value = true
        try {
            const response = await get(`/faltas/aluno/${alunoId}`, params)
            faltas.value = response.data || response
            return faltas.value
        } finally {
            loading.value = false
        }
    }
    
    // Buscar resumo de faltas de um aluno
    const fetchResumoFaltas = async (alunoId) => {
        loading.value = true
        try {
            const response = await get(`/faltas/aluno/${alunoId}/resumo`)
            resumoFaltas.value = response
            return response
        } finally {
            loading.value = false
        }
    }
    
    // Buscar faltas de uma turma
    const fetchFaltasByTurma = async (turmaId, params = {}) => {
        loading.value = true
        try {
            const response = await get(`/faltas/turma/${turmaId}`, params)
            return response
        } finally {
            loading.value = false
        }
    }
    
    // Marcar falta manualmente
    const marcarFalta = async (data) => {
        loading.value = true
        try {
            const response = await post('/faltas/marcar', data)
            return response
        } finally {
            loading.value = false
        }
    }
    
    // Justificar falta
    const justificarFalta = async (faltaId, justificativa) => {
        loading.value = true
        try {
            const response = await post(`/faltas/${faltaId}/justificar`, { justificativa })
            return response
        } finally {
            loading.value = false
        }
    }
    
    return {
        faltas,
        resumoFaltas,
        loading,
        fetchFaltasByAluno,
        fetchResumoFaltas,
        fetchFaltasByTurma,
        marcarFalta,
        justificarFalta
    }
}