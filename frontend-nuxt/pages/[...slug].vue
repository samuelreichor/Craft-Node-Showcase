<script lang="ts" setup>
  import { CraftPage } from 'craft-vue-sdk';
  import PageHome from '~/templates/pages/home.vue';
  import PageNews from '~/templates/pages/news.vue';
  import Page404 from '~/templates/pages/404.vue';
  import PageBlog from '~/templates/pages/blog.vue'

  import BlockImageText from '~/templates/blocks/imageText.vue'
  import BlockHeadline from '~/templates/blocks/headline.vue'

  const route = useRoute()
  const { data, error } = await useQueryBuilder('entries')
    .uri(route.params.slug || '__home__')
    .one()

  if (error.value) {
    throw createError({
      ...error.value,
      statusMessage: `Could not fetch data from endpoint`,
    });
  }

  const mapping = {
    pages: {
      'home': PageHome,
      'news': PageNews,
      'blog': PageBlog,
      '404': Page404,
    },
    components: {
      'imageText': BlockImageText,
      'headline': BlockHeadline,
    }
  };
</script>

<template>
  <div>
    <CraftPageLocal v-if="data" :config="mapping" :content="data" />
  </div>
</template>
 