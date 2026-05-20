import { useApi } from './useApi'

export const useUpload = () => {
    const { post } = useApi()
    const uploadProgress = ref(0)
    const uploading = ref(false)

    const uploadFile = async (file, endpoint, onProgress) => {
        uploading.value = true
        uploadProgress.value = 0
        
        const formData = new FormData()
        formData.append('file', file)
        
        // Simular progresso (ajuste conforme necessidade)
        const interval = setInterval(() => {
            if (uploadProgress.value < 90) {
                uploadProgress.value += 10
                if (onProgress) onProgress(uploadProgress.value)
            }
        }, 200)
        
        try {
            const result = await post(endpoint, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })
            clearInterval(interval)
            uploadProgress.value = 100
            return result
        } catch (error) {
            clearInterval(interval)
            throw error
        } finally {
            uploading.value = false
        }
    }

    return { uploadFile, uploadProgress, uploading }
}