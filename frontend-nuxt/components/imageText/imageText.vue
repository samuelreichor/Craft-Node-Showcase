<script setup lang="ts">
  const props = defineProps({
    image: {
      type: Object,
      default: () => undefined,
    },
    text: {
      type: String,
      default: () => '',
    },
  });
</script>

<template>
  <div class="flex gap-10 border border-black">
    <div v-if="props.image" class="w-1/2">
      <img :src="props.image.url" class="w-full h-full object-fit min-h-96">
    </div>
    <div class="w-1/2">
      {{ props.text }}
    </div>
  </div>
</template>
