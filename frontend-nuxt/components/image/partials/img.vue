<template>
  <picture v-if="props.image" :class="compDefaults.name">
    <source sizes="100vw" :srcset="props.image.srcset" />
    <img
      :loading="props.lazy ? 'lazy' : 'eager'"
      :src="props.image.src"
      :alt="props.image.alt"
      :width="props.image.width"
      :height="props.image.height"
      :style="{
        'object-fit': props.objectFit,
        'object-position': setFocalPoint(),
      }"
    />
  </picture>
</template>

<script setup lang="ts">
  const props = defineProps({
    image: {
      required: true,
      type: Object,
      default: () => {},
    },
    lazy: {
      type: Boolean,
      default: () => true,
    },
    objectFit: {
      type: String,
      default: () => 'contain',
    },
  });

  const compDefaults = {
    name: 'c-img',
  };

  const setFocalPoint = () => {
    return `${props.image.focalPoint[0] * 100}% ${
      props.image.focalPoint[1] * 100
    }%`;
  };
</script>
