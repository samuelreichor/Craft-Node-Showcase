<template>
  <div class="flex gap-10">
    <h1 class="w-1/2">{{data.entry.heroHeadline}}</h1>
    <img class="w-1/2" :src="imgUrl(data.entry.heroImage[0].url)">
  </div>
</template>

<script setup lang="ts">
  const route = useRoute()

  const { data } = await useAsyncGql({
    operation: 'pages',
    variables: { 
      slug: route.params.slug, 
    }
  });
  
  const imgUrl = ($url: string) => {
    const beUrl = "https://template-headless.ddev.site"
    return beUrl + $url
  }
</script>