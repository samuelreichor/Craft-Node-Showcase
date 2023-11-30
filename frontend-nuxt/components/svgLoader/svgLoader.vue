<template>
  <component :is="icon" class="h-full" />
</template>

<script setup>
const props = defineProps({
  name: {
    type: String,
    required: true,
  },
});

const icon = defineAsyncComponent(() => import(`../../assets/images/svg/${props.name}.svg`));
</script>
