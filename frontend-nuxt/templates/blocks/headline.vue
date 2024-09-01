<script setup lang="ts">
  defineProps({
    type: {
      type: String,
      required: true,
    },
    headlineTag: {
      type: Object,
      required: true,
    },
    text: {
      type: String,
      required: true,
    }
  })
</script>

<template>
  <Headline :as="headlineTag.value" class="border border-black">
    {{ text }}
  </Headline>
</template>