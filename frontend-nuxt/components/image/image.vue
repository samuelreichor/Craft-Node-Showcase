<template>
  <div
    v-if="props.image"
    :class="[
      compDefaults.name,
      twMerge(compDefaults.classes.root, props.customClasses),
    ]"
  >
    <Figure
      v-if="props.showCaption && props.image.caption"
      :image="props.image"
      :lazy="props.lazy"
      :object-fit="props.objectFit"
      :caption="props.image.caption"
    />
    <Img
      v-else
      :image="props.image"
      :lazy="props.lazy"
      :object-fit="props.objectFit"
    />
  </div>
</template>

<script setup lang="ts">
  import { twMerge } from 'tailwind-merge';
  import Figure from './partials/figure.vue';
  import Img from './partials/img.vue';

  const props = defineProps({
    customClasses: {
      type: String,
      default: () => '',
    },
    image: {
      type: Object,
      default: () => {},
    },
    showCaption: {
      type: Boolean,
      default: () => false,
    },
    lazy: {
      type: Boolean,
      default: () => true,
    },
    objectFit: {
      type: String,
      default: () => '',
    },
  });

  const compDefaults = {
    name: 'c-image',
    classes: {
      root: '',
    },
  };
</script>
