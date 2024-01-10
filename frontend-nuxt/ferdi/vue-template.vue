<template>
  <div
    :class="[
      compDefaults.name,
      twMerge(compDefaults.classes.root, props.customClasses),
    ]"
  >
    {{ compDefaults.name }} rendered
  </div>
</template>

<script setup lang="ts">
  import { twMerge } from 'tailwind-merge';
  const props = defineProps({
    customClasses: {
      type: String,
      default: () => '',
    },
  });

  const compDefaults = {
    name: 'c-<%= moduleName.replace(" - ", "_").charAt(0) %><%= moduleName.replace("-", "_").slice(1) %>',
    classes: {
      root: '',
    },
  };

  onMounted(() => {
    console.log('Init: ', data.name);
  });
</script>
