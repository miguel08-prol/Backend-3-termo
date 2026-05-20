<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="modelValue" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900/80 to-slate-950/80 backdrop-blur-md" @click="$emit('update:modelValue', false)"></div>
        
        <div class="relative bg-gradient-to-br from-white to-slate-50 dark:from-slate-900 dark:to-slate-950 rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto animate-slide-up">
          <!-- Header com gradiente -->
          <div class="relative overflow-hidden rounded-t-3xl">
            <div class="absolute inset-0 bg-gradient-to-r from-red-500 to-red-600 opacity-10"></div>
            <div class="relative flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-800">
              <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg shadow-red-500/30">
                  <Icon :name="icon" class="w-6 h-6 text-white" />
                </div>
                <div>
                  <h2 class="text-2xl font-black bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300 bg-clip-text text-transparent">{{ title }}</h2>
                  <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">ID: #{{ data?.id || '—' }}</p>
                </div>
              </div>
              <button @click="$emit('update:modelValue', false)" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all duration-200 flex items-center justify-center group">
                <Icon name="heroicons:x-mark-20-solid" class="w-5 h-5 text-slate-500 group-hover:text-red-500 transition-colors" />
              </button>
            </div>
          </div>
          
          <div class="p-6 space-y-6">
            <slot />
          </div>
          
          <div v-if="$slots.footer" class="p-6 bg-slate-50 dark:bg-slate-800/50 rounded-b-3xl border-t border-slate-200 dark:border-slate-800">
            <slot name="footer" />
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
defineProps({
  modelValue: { type: Boolean, required: true },
  title: { type: String, default: '' },
  icon: { type: String, default: 'heroicons:document-text-20-solid' },
  data: { type: Object, default: null }
})

defineEmits(['update:modelValue'])
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.animate-slide-up {
  animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}
</style>