// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  
  modules: [
    '@nuxtjs/tailwindcss',
    '@nuxt/icon',
    '@nuxtjs/color-mode',
    '@pinia/nuxt',
    '@vueuse/nuxt'
  ],
  
  colorMode: {
    classSuffix: '',
    preference: 'light',
    fallback: 'light',
    storageKey: 'maintsys-color-mode'
  },
  
  icon: {
    serverBundle: {
      collections: ['heroicons', 'mdi', 'carbon', 'fa6-solid']
    }
  },
  
  css: ['~/assets/css/main.css'],
  
  app: {

    head: {
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' }
      ],
            link: [
        // Adicione esta linha:
        { rel: 'icon', type: 'image/x-icon', href: '../icon/favicon.svg' }
      ]
    }
  },
  
  // Adicione isso para evitar problemas de hidratação
  ssr: false,
  
  // Configuração do router
  router: {
    options: {
      strict: true
    }
  },
  
  // Garantir que o cliente side rendering funcione bem
  nitro: {
    preset: 'node-server'
  },

    runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000/api'
    }
  },
})