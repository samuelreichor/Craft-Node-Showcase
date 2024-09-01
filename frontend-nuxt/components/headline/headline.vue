<script setup lang="ts">
  const props = defineProps({
    size: {
      type: String as PropType<HeadlineSizes>,
      default: () => 'h2',
    },
    as: {
      type: String,
      default: () => 'h2',
    },
  });

  const sizes = {
    mega: 'text-10xl font-light',
    h1: 'text-6xl font-light',
    h2: 'text-5xl font-light',
    h3: 'text-4xl font-light',
    h4: 'text-2xl font-bold',
  };

  export type HeadlineSizes = keyof typeof sizes;
</script>

<template>
  <component :is="props.as" :class="[sizes[props.size]]">
    <slot />
  </component>
</template>
