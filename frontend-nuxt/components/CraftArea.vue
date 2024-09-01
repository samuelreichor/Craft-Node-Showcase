<script lang="ts" setup>
  const props = defineProps({
    content: {
      type: Object,
      required: true,
    }
  })

  const config = inject('config');

  function getCurrentComponent(component: object) {
    if (!config || !('components' in config)) {
      throw new Error('Configuration is missing or invalid.');
    }

    if (!('type' in component)) {
      throw new Error('props.content has no key named type');
    }

    const cName = component.type;

    const componentEl = config.components[cName];
    if (!componentEl) {
      throw new Error(`No page found for section handle: ${componentEl}`);
    }

    return componentEl
  }


  console.log(props.content)

</script>

<template>
  <div v-for="(component, index) in props.content" :key="index">
    <component :is="getCurrentComponent(component)" v-bind="component"/>
  </div>
</template>