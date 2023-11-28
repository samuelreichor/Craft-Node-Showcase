<template>
    <h1>{{gql.data.entry.heroHeadline}}</h1>
</template>

<script setup>
/*     const { data } = await useAsyncGql({
        operation: 'home',
        variables: { limit: 5 }
    }); */

    const route = useRoute();

    const query = `{
        entry: entry(section: "home") {
            ... on home_home_Entry {
            heroHeadline
            }
        }
    }`;

    const token = route.query.token;

    const { data: gql } = await useFetch('https://template-headless.ddev.site/gql', {
            params: { query, token },
        });
</script>