<template>
  <slot />
  <MainThreadTimer v-if="environment === 'dev'" />
</template>

<script setup>
  import seoMaticQuery from '../queries/seoMatic';
  import seoMaticDataTransformer from '../composables/seoMaticHelper';

  const { environment } = useRuntimeConfig().public;
  const { path } = useRoute();

  const seoRawData = ref(null);
  const seomaticData = ref(null);

  const variables = {
    slug: path,
  };

  const setSeoData = () => {
    useHead({
      htmlAttrs: {
        lang: 'de',
      },
      title: seomaticData.value.title,
      meta: seomaticData.value.meta,
      script: seomaticData.value.script,
      link: seomaticData.value.link,
    });
  };

  onMounted(async () => {
    try {
      seoRawData.value = await graphQlHelper(seoMaticQuery, variables);
      seomaticData.value = seoMaticDataTransformer(seoRawData.value.seomatic);
      setSeoData();
    } catch (error) {
      throw new Error(error);
    }
  });
</script>
