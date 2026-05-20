<template>
  <div class="relative" ref="notificationRef">
    <button 
      @click="showNotifications = !showNotifications"
      class="relative w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors flex items-center justify-center"
    >
      <Icon name="heroicons:bell-20-solid" class="w-6 h-6 text-slate-500 dark:text-slate-400" />
      <span v-if="notificacoesNaoLidas > 0" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center animate-pulse">
        {{ notificacoesNaoLidas > 9 ? '9+' : notificacoesNaoLidas }}
      </span>
    </button>
    
    <!-- Dropdown de Notificações -->
    <Transition name="slide-down">
      <div v-if="showNotifications" class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-700 z-50 overflow-hidden">
        <div class="p-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
          <h3 class="font-bold text-slate-800 dark:text-white">Notificações</h3>
          <button @click="marcarTodasComoLidas" class="text-xs text-blue-500 hover:text-blue-600">Marcar todas como lidas</button>
        </div>
        
        <div class="max-h-96 overflow-y-auto">
          <div v-for="notif in notificacoes" :key="notif.id" 
               @click="lerNotificacao(notif)"
               :class="[
                   'p-4 border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors',
                   !notif.lida ? 'bg-blue-50/50 dark:bg-blue-500/10' : ''
               ]">
            <div class="flex gap-3">
              <div :class="[
                  'w-10 h-10 rounded-xl flex items-center justify-center',
                  notif.tipo === 'os' ? 'bg-blue-100 text-blue-500' :
                  notif.tipo === 'maquina' ? 'bg-amber-100 text-amber-500' :
                  'bg-emerald-100 text-emerald-500'
              ]">
                <Icon :name="notif.tipo === 'os' ? 'heroicons:wrench-20-solid' : 
                            notif.tipo === 'maquina' ? 'heroicons:computer-desktop-20-solid' : 
                            'heroicons:check-badge-20-solid'" class="w-5 h-5" />
              </div>
              <div class="flex-1">
                <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ notif.titulo }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ notif.mensagem }}</p>
                <p class="text-[10px] text-slate-400 mt-2">{{ formatarTempo(notif.created_at) }}</p>
              </div>
              <div v-if="!notif.lida" class="w-2 h-2 rounded-full bg-blue-500"></div>
            </div>
          </div>
          
          <div v-if="notificacoes.length === 0" class="p-8 text-center">
            <Icon name="heroicons:bell-slash-20-solid" class="w-12 h-12 text-slate-300 dark:text-slate-600 mx-auto mb-3" />
            <p class="text-slate-500 dark:text-slate-400 text-sm">Nenhuma notificação</p>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { onClickOutside } from '@vueuse/core'
import { useApi } from '~/composables/useApi'
import { useToast } from '~/composables/useToast'

const { get, post } = useApi()
const { show: toast } = useToast()

const showNotifications = ref(false)
const notificationRef = ref(null)
const notificacoes = ref([])
const notificacoesNaoLidas = computed(() => notificacoes.value.filter(n => !n.lida).length)

let pollingInterval = null

const carregarNotificacoes = async () => {
    try {
        const data = await get('/notificacoes')
        notificacoes.value = data
    } catch (error) {
        console.error('Erro ao carregar notificações:', error)
    }
}

const marcarTodasComoLidas = async () => {
    try {
        await post('/notificacoes/marcar-todas')
        await carregarNotificacoes()
        toast('Todas as notificações marcadas como lidas')
    } catch (error) {
        console.error('Erro ao marcar notificações:', error)
    }
}

const lerNotificacao = async (notif) => {
    if (!notif.lida) {
        try {
            await post(`/notificacoes/${notif.id}/ler`)
            notif.lida = true
        } catch (error) {
            console.error('Erro ao marcar como lida:', error)
        }
    }
    // Navegar para a página relacionada
    if (notif.link) {
        navigateTo(notif.link)
        showNotifications.value = false
    }
}

const formatarTempo = (data) => {
    const date = new Date(data)
    const agora = new Date()
    const diff = Math.floor((agora - date) / 1000 / 60) // minutos
    
    if (diff < 1) return 'Agora mesmo'
    if (diff < 60) return `${diff} min atrás`
    if (diff < 1440) return `${Math.floor(diff / 60)} horas atrás`
    return date.toLocaleDateString('pt-BR')
}

onClickOutside(notificationRef, () => {
    showNotifications.value = false
})

onMounted(() => {
    carregarNotificacoes()
    // Polling a cada 30 segundos
    pollingInterval = setInterval(carregarNotificacoes, 30000)
})

onUnmounted(() => {
    if (pollingInterval) clearInterval(pollingInterval)
})
</script>

<style scoped>
.slide-down-enter-active,
.slide-down-leave-active {
    transition: all 0.2s ease;
}

.slide-down-enter-from,
.slide-down-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>