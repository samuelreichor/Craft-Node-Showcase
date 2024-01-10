import svgLoader from 'vite-svg-loader';
import path from 'path';
import possibleTypes from './queries/configs/possibleTypes.json';

export default defineNuxtConfig({
  modules: ['@nuxtjs/tailwindcss', '@nuxtjs/apollo'],
  vite: {
    resolve: {
      alias: {
        '@components': path.resolve('./components'),
        '@assets': path.resolve('./assets'),
      },
    },
    server: {
      fs: {
        strict: false,
      },
      origin: 'https://localhost:3000',
      port: 3000,
      strictPort: true,
    },
    plugins: [svgLoader()],
  },
  nitro: {
    compressPublicAssets: true,
  },
  tailwindcss: { cssPath: '~/assets/css/app.pcss' },
  build: {
    transpile: ['tslib'],
  },
  apollo: {
    clients: {
      default: {
        httpEndpoint: `${process.env.BACKEND_URL}/gql/`,
        inMemoryCacheOptions: {
          possibleTypes,
        },
      },
    },
  },
  app: {
    pageTransition: { name: 'page', mode: 'out-in' },
  },
  runtimeConfig: {
    public: {
      environment: process.env.NUXT_ENVIRONMENT,
      gqlEndpoint: `${process.env.BACKEND_URL}/gql/`,
    },
  },
  devtools: {
    enabled: true,
  },
});
