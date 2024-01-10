<template>
  <component
    :is="props.tag"
    v-if="props.text"
    :class="[
      compDefaults.name,
      twMerge(compDefaults.classes.root, selectedSize, customClasses),
    ]"
  >
    {{ text }}
  </component>
</template>

<script setup lang="ts">
  import { twMerge } from 'tailwind-merge';

  const props = defineProps({
    customClasses: {
      type: String,
      default: () => '',
    },
    text: {
      required: true,
      type: String,
      default: () => '',
    },
    tag: {
      type: String,
      default: () => 'h2',
    },
    size: {
      type: String,
      default: () => 'h2',
    },
  });

  const compDefaults = {
    name: 'c-headline',
    classes: {
      root: 'headline-root',
    },
  };

  const variants = [
    { name: 'h1', class: 'h1-styling' },
    { name: 'h2', class: 'h2-styling' },
    { name: 'h3', class: 'h3-styling' },
    { name: 'h4', class: 'h4-styling' },
    { name: 'h5', class: 'h5-styling' },
    { name: 'h6', class: 'h6-styling' },
  ];

  const selectedSize = computed(() => {
    const size = variants.find((v) => v.name === props.size);
    if (size) {
      return size.class;
    }
    return '';
  });
</script>
