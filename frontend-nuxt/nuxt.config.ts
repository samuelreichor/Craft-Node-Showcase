// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-04-03',
  devtools: {
    enabled: true,

    timeline: {
      enabled: true,
    },
  },
  modules: ['@nuxt/eslint', '@nuxtjs/tailwindcss'],
  nitro: {
    compressPublicAssets: true,
  },

  // TODO: Add prerender hook for static rendering
});