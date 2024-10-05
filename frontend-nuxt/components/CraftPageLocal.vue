<script lang="ts" setup>
  import type { Config } from '~/types/types'

  const props = defineProps({
    config: {
      type: Object as PropType<Config>,
      required: true,
    },
    content: {
      type: Object,
      required: true,
    }
  })

  function getCurrentSectionHandle(): string {
    if (!('sectionHandle' in props.content)) {
      return '404';
    }

    return props.content.sectionHandle;
  }

  function getCurrentPage() {
    if (!props.config || !('pages' in props.config)) {
      throw new Error('Configuration is missing or invalid.');
    }

    const currentSectionHandle = getCurrentSectionHandle();

    if (!currentSectionHandle) {
      throw new Error('Invalid section handle.');
    }


    const pageComponent = props.config.pages[currentSectionHandle];
    if (!pageComponent) {
      console.error(`No mapped page found for page handle: ${currentSectionHandle}`);
      return null;
    }

    return pageComponent;
  }

  provide('config', props.config)
</script>

<template>
  <div>
    <component :is="getCurrentPage()" v-bind="props.content" />
  </div>
</template>