<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="modelValue" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900/80 to-slate-950/80 backdrop-blur-md" @click="$emit('update:modelValue', false)"></div>
        
        <div class="relative bg-gradient-to-br from-white to-slate-50 dark:from-slate-900 dark:to-slate-950 rounded-3xl shadow-2xl w-full max-w-md animate-scale-up">
          <div class="text-center p-8">
            <!-- Ícone animado -->
            <div class="relative inline-flex mb-4">
              <div class="absolute inset-0 rounded-full animate-ping" :class="iconBgClass"></div>
              <div class="relative w-20 h-20 rounded-full flex items-center justify-center shadow-lg" :class="iconBgClass">
                <Icon :name="icon" class="w-10 h-10 text-white" />
              </div>
            </div>
            
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">{{ title }}</h3>
            <p class="text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
              {{ message }}
              <strong v-if="itemName" class="text-slate-900 dark:text-white block mt-2 text-sm">{{ itemName }}</strong>
            </p>
            
            <div class="flex gap-3">
              <button @click="$emit('update:modelValue', false)" class="flex-1 px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all duration-200">
                Cancelar
              </button>
              <button @click="$emit('confirm')" :disabled="loading" class="flex-1 px-4 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-2 shadow-lg" :class="confirmButtonClass">
                <Icon v-if="loading" name="heroicons:arrow-path-20-solid" class="w-5 h-5 animate-spin" />
                <Icon v-else :name="confirmIcon" class="w-5 h-5" />
                {{ loading ? 'Processando...' : confirmText }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: { type: Boolean, required: true },
  title: { type: String, default: 'Confirmar Ação' },
  message: { type: String, default: 'Tem certeza que deseja realizar esta ação?' },
  itemName: { type: String, default: '' },
  variant: { type: String, default: 'danger' },
  loading: { type: Boolean, default: false },
  confirmText: { type: String, default: 'Confirmar' }
})

defineEmits(['update:modelValue', 'confirm'])

const iconMap = {
  danger: { icon: 'heroicons:exclamation-triangle-20-solid', bg: 'bg-gradient-to-br from-red-500 to-red-600', shadow: 'shadow-red-500/30', button: 'bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white shadow-red-500/30' },
  warning: { icon: 'heroicons:exclamation-circle-20-solid', bg: 'bg-gradient-to-br from-amber-500 to-amber-600', shadow: 'shadow-amber-500/30', button: 'bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white shadow-amber-500/30' },
  info: { icon: 'heroicons:information-circle-20-solid', bg: 'bg-gradient-to-br from-blue-500 to-blue-600', shadow: 'shadow-blue-500/30', button: 'bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white shadow-blue-500/30' },
  success: { icon: 'heroicons:check-circle-20-solid', bg: 'bg-gradient-to-br from-emerald-500 to-emerald-600', shadow: 'shadow-emerald-500/30', button: 'bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white shadow-emerald-500/30' }
}

const iconMapValue = iconMap[props.variant] || iconMap.danger
const icon = computed(() => iconMapValue.icon)
const iconBgClass = computed(() => iconMapValue.bg)
const confirmButtonClass = computed(() => iconMapValue.button)
const confirmIcon = computed(() => props.variant === 'danger' ? 'heroicons:trash-20-solid' : 'heroicons:check-20-solid')
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

.animate-scale-up {
  animation: scaleUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes scaleUp {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
</style>