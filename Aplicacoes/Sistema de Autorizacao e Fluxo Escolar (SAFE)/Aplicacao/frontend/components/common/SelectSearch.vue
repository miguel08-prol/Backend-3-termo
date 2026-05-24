<template>
  <div class="relative" ref="dropdownRef">
    <div class="relative">
      <input
        ref="inputRef"
        type="text"
        :value="displayText"
        @focus="openDropdown"
        @input="handleInput"
        @click.stop="openDropdown"
        :placeholder="placeholder"
        :required="required"
        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all pr-10"
        autocomplete="off"
      />
      <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-1 pointer-events-none">
        <Icon 
          v-if="loading" 
          name="heroicons:arrow-path-20-solid" 
          class="w-4 h-4 text-slate-400 animate-spin" 
        />
        <Icon 
          v-else 
          name="heroicons:chevron-down-20-solid" 
          :class="['w-5 h-5 text-slate-400 transition-transform', isOpen ? 'rotate-180' : '']" 
        />
      </div>
    </div>
    
    <!-- Dropdown de opções -->
    <Teleport to="body">
      <div 
        v-if="isOpen" 
        class="fixed z-[100] bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg shadow-xl overflow-hidden"
        :style="dropdownStyle"
      >
        <div class="max-h-60 overflow-y-auto">
          <div
            v-for="option in filteredOptions"
            :key="getOptionValue(option)"
            @click="selectOption(option)"
            :class="[
              'px-4 py-2.5 cursor-pointer transition-colors text-sm hover:bg-slate-100 dark:hover:bg-slate-700',
              isSelected(option) ? 'bg-red-50 dark:bg-red-500/20 text-red-600' : 'text-slate-700 dark:text-slate-300'
            ]"
          >
            <div class="flex flex-col">
              <span class="font-medium">{{ getOptionLabel(option) }}</span>
              <span v-if="getOptionSubtitle(option)" class="text-xs text-slate-400">{{ getOptionSubtitle(option) }}</span>
            </div>
          </div>
          
          <div v-if="filteredOptions.length === 0 && !loading" class="px-4 py-8 text-center">
            <Icon name="heroicons:user-group-20-solid" class="w-8 h-8 mx-auto mb-2 text-slate-300 dark:text-slate-600" />
            <p class="text-sm text-slate-400">Nenhum resultado encontrado</p>
          </div>
          
          <div v-if="loading" class="px-4 py-8 text-center">
            <Icon name="heroicons:arrow-path-20-solid" class="w-6 h-6 mx-auto mb-2 animate-spin text-slate-400" />
            <p class="text-sm text-slate-400">Carregando...</p>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, computed, watch, nextTick, onBeforeUnmount } from 'vue'

const props = defineProps({
  modelValue: { type: [String, Number], default: null },
  options: { type: Array, default: () => [] },
  optionLabel: { type: String, default: 'name' },
  optionValue: { type: String, default: 'id' },
  optionSubtitle: { type: String, default: '' },
  placeholder: { type: String, default: 'Digite para buscar...' },
  loading: { type: Boolean, default: false },
  required: { type: Boolean, default: false }
})

const emit = defineEmits(['update:modelValue', 'search'])

const isOpen = ref(false)
const searchTerm = ref('')
const dropdownRef = ref(null)
const inputRef = ref(null)
const dropdownStyle = ref({})

const selectedOption = computed(() => {
  return props.options.find(opt => getOptionValue(opt) === props.modelValue)
})

const displayText = computed(() => {
  if (isOpen.value || searchTerm.value) {
    return searchTerm.value
  }
  if (selectedOption.value) {
    return getOptionLabel(selectedOption.value)
  }
  return ''
})

const filteredOptions = computed(() => {
  if (!searchTerm.value) return props.options
  const term = searchTerm.value.toLowerCase()
  return props.options.filter(opt => {
    const label = getOptionLabel(opt).toLowerCase()
    const subtitle = getOptionSubtitle(opt).toLowerCase()
    return label.includes(term) || subtitle.includes(term)
  })
})

const getOptionLabel = (option) => {
  if (typeof option === 'object') {
    return option[props.optionLabel] || option.name || 'Sem nome'
  }
  return String(option)
}

const getOptionValue = (option) => {
  if (typeof option === 'object') {
    return option[props.optionValue] || option.id
  }
  return option
}

const getOptionSubtitle = (option) => {
  if (typeof option === 'object' && props.optionSubtitle) {
    return option[props.optionSubtitle] || ''
  }
  return ''
}

const isSelected = (option) => {
  return getOptionValue(option) === props.modelValue
}

const updateDropdownPosition = () => {
  if (!dropdownRef.value) return
  const rect = dropdownRef.value.getBoundingClientRect()
  dropdownStyle.value = {
    top: `${rect.bottom + window.scrollY + 4}px`,
    left: `${rect.left + window.scrollX}px`,
    width: `${rect.width}px`,
    maxWidth: `${rect.width}px`
  }
}

const openDropdown = () => {
  if (isOpen.value) return
  isOpen.value = true
  searchTerm.value = ''
  nextTick(() => {
    updateDropdownPosition()
    inputRef.value?.focus()
  })
}

const closeDropdown = () => {
  isOpen.value = false
  searchTerm.value = ''
}

const handleInput = (event) => {
  searchTerm.value = event.target.value
  if (!isOpen.value) {
    isOpen.value = true
  }
  nextTick(updateDropdownPosition)
  emit('search', searchTerm.value)
}

const selectOption = (option) => {
  emit('update:modelValue', getOptionValue(option))
  closeDropdown()
}

// Fechar ao clicar fora
const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    closeDropdown()
  }
}

// Fechar ao pressionar ESC
const handleKeyDown = (event) => {
  if (event.key === 'Escape') {
    closeDropdown()
    inputRef.value?.blur()
  }
}

// Atualizar posição quando rolar a página
const handleScroll = () => {
  if (isOpen.value) {
    updateDropdownPosition()
  }
}

// Sincronizar quando o modelValue mudar externamente
watch(() => props.modelValue, () => {
  closeDropdown()
})

// Observar mudanças nas opções para re-posicionar
watch(() => props.options.length, () => {
  if (isOpen.value) {
    nextTick(updateDropdownPosition)
  }
})

// Event listeners
onMounted(() => {
  document.addEventListener('click', handleClickOutside)
  document.addEventListener('keydown', handleKeyDown)
  window.addEventListener('scroll', handleScroll, true)
  window.addEventListener('resize', updateDropdownPosition)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
  document.removeEventListener('keydown', handleKeyDown)
  window.removeEventListener('scroll', handleScroll, true)
  window.removeEventListener('resize', updateDropdownPosition)
})
</script>