<template>
    <div :class="[
        'bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden',
        'transition-all duration-300 hover:shadow-md',
        paddingClasses[noPadding ? 'none' : 'default']
    ]">
        <div v-if="$slots.header" class="border-b border-slate-100 p-6">
            <slot name="header" />
        </div>
        
        <div :class="noPadding ? '' : 'p-6'">
            <slot />
        </div>
        
        <div v-if="$slots.footer" class="border-t border-slate-100 p-6 bg-slate-50/50">
            <slot name="footer" />
        </div>
    </div>
</template>

<script setup>
defineProps({
    noPadding: { type: Boolean, default: false }
})

const paddingClasses = {
    default: 'p-6',
    none: ''
}
</script>