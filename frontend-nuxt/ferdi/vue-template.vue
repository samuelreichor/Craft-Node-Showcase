<template>
  <div :class="[name, data.classes.root]">{{ data.name }} rendered</div>
</template>

<script setup lang="ts">
const props = defineProps({
  headline: {
    type: String,
    default: () => '',
  },
});

const data = {
  name: 'c-<%= moduleName.replace(" - ", "_").charAt(0) %><%= moduleName.replace("-", "_").slice(1) %>',
  classes: {
    root: '',
  },
};

onMounted(() => {
  console.log('Init: ', data.name);
});
</script>
