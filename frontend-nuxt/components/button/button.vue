<template>
  <a
    v-if="props.button"
    :class="[
      compDefaults.name,
      twMerge(compDefaults.classes.root, selectedVariant, props.customClasses),
    ]"
    :href="btnData.btnLink"
    :target="btnData.btnTarget"
  >
    {{ btnData.btnText }}
  </a>
</template>

<script setup lang="ts">
  import { twMerge } from 'tailwind-merge';

  const props = defineProps({
    customClasses: {
      type: String,
      default: () => '',
    },
    button: {
      required: true,
      type: Object,
      default: () => {},
    },
    variant: {
      type: String,
      default: () => 'primary',
    },
  });

  const compDefaults = {
    name: 'c-button',
    classes: {
      root: 'transition-all duration-200 cursor-pointer inline-block !leading-none tracking-wide relative no-underline',
    },
  };

  const variants = [
    {
      name: 'primary',
      class:
        'c-button-primary border-2 border-primary hover:bg-transparent hover:text-black bg-primary text-white pb-3 pt-2.5 px-6',
    },
    {
      name: 'secondary',
      class:
        'c-button-secondary border-2 border-primary bg-transparent text-black hover:bg-primary hover:text-white pb-3 pt-2.5 px-6 ',
    },
    {
      name: 'link',
      class: 'c-button-link underline underline-offset-2 hover:text-primary',
    },
  ];

  const selectedVariant = computed(() => {
    const variant = variants.find((v) => v.name === props.variant);
    if (variant) {
      return variant.class;
    }
    return '';
  });

  const btnData = computed(() => ({
    btnText: props.button.text,
    btnLink: props.button.url,
    btnTarget: props.button.target || '_self',
  }));
</script>
