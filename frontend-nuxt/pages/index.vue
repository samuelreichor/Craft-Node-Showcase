<template>
  <div v-if="data && data.entry">
    <h1>{{ data.entry.heroHeadline }}</h1>
  </div>
</template>

<script setup lang="ts">
import homeQuery from '../queries/home';

const route = useRoute();
const { token } = route.query.token;
const data = ref(null);

const variables = {
  limit: 5,
};

onMounted(async () => {
  try {
    data.value = await graphQlHelper(homeQuery, variables, token);
  } catch (error) {
    throw error('Error: ', error);
  }
});
</script>
