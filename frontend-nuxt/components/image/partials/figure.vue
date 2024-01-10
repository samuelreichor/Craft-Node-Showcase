<template>
  <figure v-if="props.image" :class="compDefaults.name">
    <Img
      :image="props.image"
      :lazy="props.lazy"
      :object-fit="props.objectFit"
    />
    <figcaption>
      <Text :text="props.caption" />
    </figcaption>
  </figure>
</template>

<script setup lang="ts">
  import Img from './img.vue';

  const props = defineProps({
    image: {
      type: Object,
      default: () => {},
    },
    lazy: {
      type: Boolean,
      default: () => true,
    },
    objectFit: {
      type: String,
      default: () => '',
    },
    caption: {
      type: String,
      default: () => '',
    },
  });

  const compDefaults = {
    name: 'c-figure',
  };
</script>
