import { h, render } from 'vue'
import AppToast from '~/components/common/AppToast.vue'

let toastContainer = null
let toastTimeout = null

export const useToast = () => {
    const show = (message, variant = 'success', duration = 3000) => {
        // Limpar toast anterior se existir
        if (toastTimeout) {
            clearTimeout(toastTimeout)
            if (toastContainer) {
                render(null, toastContainer)
            }
        }
        
        if (!toastContainer) {
            toastContainer = document.createElement('div')
            document.body.appendChild(toastContainer)
        }
        
        const vnode = h(AppToast, { message, variant, duration })
        render(vnode, toastContainer)
        
        toastTimeout = setTimeout(() => {
            render(null, toastContainer)
            toastTimeout = null
        }, duration + 500)
    }
    
    return { show }
}