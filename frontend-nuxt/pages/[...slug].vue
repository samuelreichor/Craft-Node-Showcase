<script lang="ts" setup>
  import { CraftPage } from 'craft-vue-sdk';
  import PageHome from '~/templates/pages/home.vue';
  import PageNews from '~/templates/pages/news.vue';

  import BlockImageText from '~/templates/blocks/imageText.vue'
  import BlockHeadline from '~/templates/blocks/headline.vue'

  const route = useRoute()

  const previewToken = route.query.token;
  const { data, error } = await useQueryBuilder('assets')
    .id(103)
    .one()

  console.log(data.value)

/*   const url = `http://127.0.0.1:62630/v1/api/entry/1/${route.params.slug || 'home'}${previewToken ? ('?token=' + previewToken) : ''}`
  const { data, error } = await useFetch<NonNullable<object>>(() => url);

  if (error.value) {
    throw createError({
      ...error.value,
      statusMessage: `Could not fetch data from ${url}`,
    });
  } */

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
<!--     <CraftPage v-if="data" :config="mapping" :content="data" /> -->
  </div>
</template>
 