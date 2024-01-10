<template>
  <component
    :is="icon"
    v-if="props.name"
    :class="[
      compDefaults.name,
      twMerge(compDefaults.classes.root, selectedSize, props.customClasses),
    ]"
  />
</template>

<script setup lang="ts">
  import { twMerge } from 'tailwind-merge';

  const props = defineProps({
    customClasses: {
      type: String,
      default: () => '',
    },
    name: {
      type: String,
      required: true,
      default: () => '',
    },
    size: {
      type: String,
      default: () => 'base',
    },
  });

  const compDefaults = {
    name: 'c-icon',
    classes: {
      root: 'inline-flex origin-center',
    },
  };

  const variants = [
    { name: 'auto', class: '' },
    { name: 'base', class: 'w-6 h-6' },
    { name: 'md', class: 'w-8 h-8' },
    { name: 'lg', class: 'w-12 h-12' },
  ];

  const selectedSize = computed(() => {
    const size = variants.find((v) => v.name === props.size);
    if (size) {
      return size.class;
    }
    return '';
  });

  const icon = defineAsyncComponent(
    () => import(`../../assets/images/svg/${props.name}.svg`),
  );
</script>
