<script setup lang="ts">
/*   defineProps({
    id: Number,
    sectionid: Number,
    title: String,
    slug: String,
  }); */
</script>

<template>
  <div v-bind="$attrs">
    {{ $attrs }}
    <h1>{{ $attrs.title }}</h1>
    <p>Section ID: {{ $attrs.sectionid }}</p>
    <p>Slug: {{ $attrs.slug }}</p>
  </div>
</template>
