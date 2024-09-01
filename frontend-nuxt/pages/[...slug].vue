<script lang="ts" setup>
  import PageHome from '~/templates/pages/home.vue';
  import PageNews from '~/templates/pages/news.vue';

  import BlockImageText from '~/templates/blocks/imageText.vue'
  import BlockHeadline from '~/templates/blocks/headline.vue'

  const route = useRoute()

  const previewToken = route.query.token; 
  const url = `http://127.0.0.1:55773/v1/entry/${route.params.slug || 'home'}${previewToken ? ('?token=' + previewToken) : ''}`
  const { data, error } = await useFetch<NonNullable<object>>(() => url);

  if (error.value) {
    throw createError({
      ...error.value,
      statusMessage: `Could not fetch data from ${url}`,
    });
  }

  const mapping = { 
    pages: {
      'home': PageHome,
      'news': PageNews,
    },
    components: {
      'imageText': BlockImageText,
      'headline': BlockHeadline,
    }
  };
</script>

<template>
  <div>
    <CraftPage v-if="data" :config="mapping" :content="data" />
  </div>
</template>