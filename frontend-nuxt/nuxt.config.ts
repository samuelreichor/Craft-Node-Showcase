import svgLoader from "vite-svg-loader";

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    modules: [
        '@nuxtjs/tailwindcss',
        '@nuxtjs/robots',
        'nuxt-graphql-client',
    ],
    vite: {
        plugins: [
            svgLoader()
        ]
    },
    nitro: {
        compressPublicAssets: true,
    },
    css: [
        '~/assets/css/app.pcss',
    ],
    runtimeConfig: {
        public: {
            GQL_HOST: 'https://template-headless.ddev.site/gql',
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
            noscript: [
                { children: 'JavaScript is required' }
            ]
        },
        pageTransition: { name: 'page', mode: 'out-in' }
    }
})
