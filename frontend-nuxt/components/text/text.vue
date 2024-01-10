<template>
  <component
    :is="props.tag"
    v-if="props.text"
    :class="[
      compDefaults.name,
      twMerge(compDefaults.classes.root, selectedSize, props.customClasses),
    ]"
  >
    <div v-html="props.text"></div>
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
    size: {
      type: String,
      default: () => 'base',
    },
    tag: {
      type: String,
      default: () => 'div',
    },
  });

  const compDefaults = {
    name: 'c-text',
    classes: {
      root: 'c-text--richText',
    },
  };

  const variants = [
    { name: 'base', class: 'text-base' },
    { name: 'lg', class: 'text-lg' },
  ];

  const selectedSize = computed(() => {
    const size = variants.find((v) => v.name === props.size);
    if (size) {
      return size.class;
    }
    return '';
  });
</script>
