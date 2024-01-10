<template>
  <div
    v-if="props.contentBuilderArr"
    :class="[
      compDefaults.name,
      twMerge(compDefaults.classes.root, props.customClasses),
    ]"
  >
    <template v-for="(item, index) in props.contentBuilderArr">
      <Image
        v-if="item.type === 'image'"
        :key="index"
        :lazy="true"
        :show-caption="true"
        object-fit="cover"
        :image="item.image[0]"
      />
    </template>
  </div>
</template>

<script setup lang="ts">
  import { twMerge } from 'tailwind-merge';

  const props = defineProps({
    customClasses: {
      type: String,
      default: () => '',
    },
    contentBuilderArr: {
      required: true,
      type: Array,
      default: () => [],
    },
  });

  const compDefaults = {
    name: 'c-contentBuilder',
    classes: {
      root: '',
    },
  };
</script>
