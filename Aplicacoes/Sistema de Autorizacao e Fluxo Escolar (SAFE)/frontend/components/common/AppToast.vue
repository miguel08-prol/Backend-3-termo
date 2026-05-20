<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="translate-y-4 opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="translate-y-4 opacity-0"
        >
            <div v-if="visible" class="fixed bottom-6 right-6 z-[200]">
                <div :class="[
                    'flex items-center gap-3 px-6 py-4 rounded-2xl shadow-2xl font-bold text-sm',
                    variantClasses[variant]
                ]">
                    <span class="text-lg">{{ icon }}</span>
                    <span class="text-white">{{ message }}</span>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
    message: { type: String, required: true },
    variant: { type: String, default: 'success' },
    duration: { type: Number, default: 3000 }
})

const visible = ref(true)

const variantClasses = {
    success: 'bg-emerald-600 shadow-emerald-500/30',
    error: 'bg-red-600 shadow-red-500/30',
    warning: 'bg-amber-600 shadow-amber-500/30',
    info: 'bg-blue-600 shadow-blue-500/30'
}

const iconMap = {
    success: '✅',
    error: '⚠️',
    warning: '⚠️',
    info: 'ℹ️'
}

const icon = iconMap[props.variant]

onMounted(() => {
    setTimeout(() => {
        visible.value = false
    }, props.duration)
})
</script>