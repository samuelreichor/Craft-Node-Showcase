// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-04-03',
  devtools: {
    enabled: true,

    timeline: {
      enabled: true,
    },
  },
  modules: ['@nuxt/eslint', '@nuxtjs/tailwindcss', '@nuxt/test-utils/module'],
  nitro: {
    compressPublicAssets: true,
  },
  plugins: [
      '~/plugins/craft-nuxt.ts'
  ]

  // TODO: Add prerender hook for static rendering
});
