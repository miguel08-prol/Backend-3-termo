<template>
  <div class="relative">
    <div 
      class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-2 border-transparent focus-within:border-blue-500 rounded-xl transition-all cursor-pointer flex items-center justify-between"
      @click="toggleDropdown"
    >
      <span :class="selectedOption ? 'text-slate-700 dark:text-slate-300 font-medium' : 'text-slate-400'">
        {{ selectedOption ? getOptionLabel(selectedOption) : placeholder }}
      </span>
      <Icon name="heroicons:chevron-down-20-solid" :class="['w-5 h-5 text-slate-400 transition-transform', isOpen ? 'rotate-180' : '']" />
    </div>
    
    <div v-if="isOpen" class="absolute z-50 w-full mt-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-lg overflow-hidden">
      <div class="p-2 border-b border-slate-100 dark:border-slate-700">
        <div class="relative">
          <Icon name="heroicons:magnifying-glass-20-solid" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
          <input 
            v-model="searchTerm"
            type="text"
            placeholder="Buscar..."
            class="w-full pl-9 pr-3 py-2 text-sm bg-slate-50 dark:bg-slate-700 rounded-lg outline-none focus:ring-1 focus:ring-blue-500"
          />
        </div>
      </div>
      
      <div class="max-h-60 overflow-y-auto">
        <div
          v-for="option in filteredOptions"
          :key="getOptionValue(option)"
          @click="selectOption(option)"
          :class="[
            'px-4 py-2 cursor-pointer transition-colors',
            isSelected(option) ? 'bg-blue-50 dark:bg-blue-500/20 text-blue-600' : 'hover:bg-slate-50 dark:hover:bg-slate-700'
          ]"
        >
          {{ getOptionLabel(option) }}
        </div>
        
        <div v-if="filteredOptions.length === 0 && loading" class="px-4 py-8 text-center text-slate-400">
          <Icon name="heroicons:arrow-path-20-solid" class="w-6 h-6 mx-auto mb-2 animate-spin" />
          Carregando...
        </div>
        
        <div v-else-if="filteredOptions.length === 0" class="px-4 py-8 text-center text-slate-400">
          Nenhum resultado encontrado
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { onClickOutside } from '@vueuse/core'

const props = defineProps({
  modelValue: { type: [String, Number], default: null },
  options: { type: Array, default: () => [] },
  optionLabel: { type: String, default: 'name' },
  optionValue: { type: String, default: 'id' },
  placeholder: { type: String, default: 'Selecione...' },
  loading: { type: Boolean, default: false }
})

const emit = defineEmits(['update:modelValue'])

const isOpen = ref(false)
const searchTerm = ref('')
const dropdownRef = ref(null)

const selectedOption = computed(() => {
  return props.options.find(opt => getOptionValue(opt) === props.modelValue)
})

const filteredOptions = computed(() => {
  if (!searchTerm.value) return props.options
  const term = searchTerm.value.toLowerCase()
  return props.options.filter(opt => 
    getOptionLabel(opt).toLowerCase().includes(term)
  )
})

const getOptionLabel = (option) => {
  if (typeof option === 'object') {
    return option[props.optionLabel] || option.name || option.id
  }
  return option
}

const getOptionValue = (option) => {
  if (typeof option === 'object') {
    return option[props.optionValue] || option.id
  }
  return option
}

const isSelected = (option) => {
  return getOptionValue(option) === props.modelValue
}

const selectOption = (option) => {
  emit('update:modelValue', getOptionValue(option))
  isOpen.value = false
  searchTerm.value = ''
}

const toggleDropdown = () => {
  isOpen.value = !isOpen.value
  if (isOpen.value) {
    searchTerm.value = ''
  }
}

// Fechar ao clicar fora
onClickOutside(dropdownRef, () => {
  isOpen.value = false
})

// Limpar busca ao fechar
watch(isOpen, (val) => {
  if (!val) searchTerm.value = ''
})
</script>