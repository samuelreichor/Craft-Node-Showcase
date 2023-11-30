import svgLoader from 'vite-svg-loader';

export default defineNuxtConfig({
  modules: ['@nuxtjs/tailwindcss', '@nuxtjs/robots', '@nuxtjs/apollo'],
  vite: {
    devServer: {
      https: {
        key: 'localhost-key.pem',
        cert: 'localhost.pem',
      },
    },
    server: {
      fs: {
        strict: false,
      },
      https: true,
      origin: 'https://localhost:3000',
      port: 3000,
      strictPort: true,
    },
    plugins: [svgLoader()],
  },
  nitro: {
    compressPublicAssets: true,
  },
  css: ['~/assets/css/app.pcss'],
  build: {
    transpile: ['tslib'],
  },
  apollo: {
    clients: {
      default: {
        httpEndpoint: process.env.GQL_HOST,
      },
    },
  },
  app: {
    head: {
      htmlAttrs: {
        lang: 'de',
      },
      title: 'Happy Coding',
      meta: [
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'msapplication-TileColor', content: '#da532c' },
        { name: 'theme-color', content: '#ffffff' },
        { name: 'description', content: 'This website is in progress' },
      ],
      noscript: [{ children: 'JavaScript is required' }],
    },
    pageTransition: { name: 'page', mode: 'out-in' },
  },
});
