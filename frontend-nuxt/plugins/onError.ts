export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.hook('apollo:error', (error) => {
    /* TODO: Add Logging System and Log Error */
    console.error(error);
  });
});
