<template>
    <button 
        :type="type"
        :disabled="loading || disabled"
        :class="[
            'inline-flex items-center justify-center gap-2 font-bold transition-all duration-200 rounded-xl',
            'focus:outline-none focus:ring-2 focus:ring-offset-2',
            variantClasses[variant],
            sizeClasses[size],
            { 'opacity-50 cursor-not-allowed': loading || disabled }
        ]"
        @click="$emit('click')"
    >
        <svg v-if="loading" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <slot />
    </button>
</template>

<script setup>
const props = defineProps({
    type: { type: String, default: 'button' },
    variant: { type: String, default: 'primary' },
    size: { type: String, default: 'md' },
    loading: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false }
})

const emit = defineEmits(['click'])

const variantClasses = {
    primary: 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
    secondary: 'bg-slate-200/70 hover:bg-slate-200 text-slate-800 dark:bg-slate-800/80 dark:hover:bg-slate-700 dark:text-slate-100 dark:ring-1 dark:ring-slate-700 focus:ring-slate-500',
    danger: 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
    success: 'bg-emerald-600 hover:bg-emerald-700 text-white focus:ring-emerald-500',
    outline: 'border-2 border-red-600 text-red-600 hover:bg-red-50 focus:ring-red-500'
}

const sizeClasses = {
    sm: 'px-4 py-2 text-sm',
    md: 'px-6 py-3 text-base',
    lg: 'px-8 py-4 text-lg'
}


const variants = {
  professor: 'bg-slate-600 hover:bg-slate-700 text-white shadow-md hover:shadow-lg transition-all duration-200',
  'professor-outline': 'border-2 border-slate-600 text-slate-600 hover:bg-slate-600 hover:text-white dark:border-slate-400 dark:text-slate-400 dark:hover:bg-slate-400 dark:hover:text-slate-900 transition-all duration-200'
}
</script>