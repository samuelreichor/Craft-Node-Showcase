import { CraftSdk, fetchBasedOnContext } from 'craft-vue-sdk';

export default defineNuxtPlugin((nuxtApp) => {

  nuxtApp.vueApp.provide('CraftSdkFetch', fetchBasedOnContext('nuxt'));
  nuxtApp.vueApp.use(CraftSdk, {
      baseUrl: 'http://127.0.0.1:55003',
      debug: true,
      registerComponents: true,
  })
})
