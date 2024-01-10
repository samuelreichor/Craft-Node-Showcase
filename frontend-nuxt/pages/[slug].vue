<template>
  <div class="flex gap-10">
    <div v-if="data && data.entries[0]">
      <h1>{{ data.entries[0].title }}</h1>
    </div>
  </div>
</template>

<script setup lang="ts">
  import pageQuery from '../queries/page';

  const data = ref(null);
  const { slug } = useRoute().params;

  const variables = {
    slug,
  };

  onMounted(async () => {
    try {
      data.value = await graphQlHelper(pageQuery, variables);
    } catch (error) {
      throw new Error(error);
    }
  });
</script>
