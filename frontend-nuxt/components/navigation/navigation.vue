<template>
  <div v-if="props.nodes" :class="[data.name, data.classes.root]">
    <ul class="flex gap-5">
      <li v-for="(node, indexNode) in props.nodes" :key="'node-' + indexNode">
        <NuxtLink :to="node.url" class="">
          {{ node.title }}
          <ul v-if="node.children">
            <li
              v-for="(child, indexChild) in node.children"
              :key="'child-' + indexNode + '-' + indexChild"
            >
              <NuxtLink :key="indexChild" :to="child.url">
                {{ child.title }}
              </NuxtLink>
            </li>
          </ul>
        </NuxtLink>
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
  const props = defineProps({
    nodes: {
      required: true,
      type: Array,
      default: () => [],
    },
  });

  const data = {
    name: 'c-navigation',
    classes: {
      root: 'bg-black text-white p-2',
    },
  };
</script>
