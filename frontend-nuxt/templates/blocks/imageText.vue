<script setup lang="ts">
  defineProps({
    type: {
      type: String,
      required: true,
    },
    image: {
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
  <ImageText :image="image[0]" :text="text"/>
</template>