<script setup lang="ts">
  import { CraftArea } from 'craft-vue-sdk';
  const props = defineProps({
    title: {
      type: String,
      required: true,
    },
    sectionHandle: {
      type: String,
      required: true,
    },
    text: {
      type: String,
      required: true,
    },
    contentbuilder: {
      type: Object,
      default: () => null,
    },
    cta: {
      type: Object,
      default: () => null
    }
  })
</script>

<template>
  <div class="mx-8 my-4">
    <Headline size="h1" as="h1">{{ props.title }}</Headline>
    {{ props.text }}
    <CraftArea v-if="props.contentbuilder" :content="props.contentbuilder" />
  </div>
</template>