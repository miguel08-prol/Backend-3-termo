// plugins/components.js
import AppButton from '~/components/common/AppButton.vue'
import AppCard from '~/components/common/AppCard.vue'
import AppModal from '~/components/common/AppModal.vue'
import AppToast from '~/components/common/AppToast.vue'
import SelectSearch from '~/components/common/SelectSearch.vue'
import ThemeToggle from '~/components/common/ThemeToggle.vue'
// import NotificationBell from '~/components/NotificationBell.vue'

export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.vueApp.component('AppButton', AppButton)
  nuxtApp.vueApp.component('AppCard', AppCard)
  nuxtApp.vueApp.component('AppModal', AppModal)
  nuxtApp.vueApp.component('AppToast', AppToast)
  nuxtApp.vueApp.component('SelectSearch', SelectSearch)
  nuxtApp.vueApp.component('ThemeToggle', ThemeToggle)
//   nuxtApp.vueApp.component('NotificationBell', NotificationBell)
})