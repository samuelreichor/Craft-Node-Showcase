<template>
  <div class="container">
    <div v-if="data && data.entry" class="col-span-full">
      <span :class="compDefaults.classes.componentHeadline">
        Headline Styles:
      </span>
      <div v-for="(size, index) in headlineSizeArr" :key="index">
        <span :class="compDefaults.classes.variantHeadline">{{ size }}:</span>
        <Headline :size="size" :text="shortText" />
      </div>

      <span :class="compDefaults.classes.componentHeadline">
        Body Text Styles:
      </span>
      <div v-for="(size, index) in bodyTextArr" :key="index">
        <span :class="compDefaults.classes.variantHeadline">{{ size }}:</span>
        <Text :size="size" :text="longText" />
      </div>

      <span :class="compDefaults.classes.componentHeadline">Fonts:</span>
      <div v-for="(font, index) in fontsArr" :key="index">
        <span :class="compDefaults.classes.variantHeadline">{{ font }}:</span>
        <Text :custom-classes="font" :text="longText" />
      </div>

      <span :class="compDefaults.classes.componentHeadline">Font Scale:</span>
      <div v-for="(fontScale, index) in fontScaleArr" :key="index">
        <span :class="compDefaults.classes.variantHeadline">
          {{ fontScale }}:
        </span>
        <Text :custom-classes="fontScale" :text="shortText" />
      </div>

      <span :class="compDefaults.classes.componentHeadline">
        Button Variants:
      </span>
      <div v-for="(variant, index) in btnVariants" :key="index">
        <span :class="compDefaults.classes.variantHeadline">
          {{ variant }}:
        </span>
        <Button :variant="variant" :button="button" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import homeQuery from '../queries/home';

  const compDefaults = {
    classes: {
      componentHeadline: 'block font-bold mb-2 mt-10',
      variantHeadline: 'block mt-4 mb-2',
    },
  };

  const data = ref(null);
  const shortText = 'The quick brown fredmansky jumps over the lazy developer.';
  const longText =
    'The quick brown fredmansky jumps over the lazy developer. That is an English-language pangram – a sentence that contains all the letters of the alphabet. The phrase is commonly used for testing typewriters and computer keyboards, displaying examples of fonts, and other applications at fredmansky.';
  const button = {
    text: 'Join us',
    url: 'https://google.com',
    target: '',
  };
  const headlineSizeArr = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
  const bodyTextArr = ['base', 'lg'];
  const fontsArr = ['font-sans', 'fonts-serif'];
  const fontScaleArr = [
    'text-xs',
    'text-sm',
    'text-base',
    'text-lg',
    'text-xl',
    'text-2xl',
    'text-3xl',
    'text-4xl',
    'text-5xl',
    'text-6xl',
    'text-7xl',
    'text-8xl',
    'text-9xl',
  ];
  const btnVariants = ['primary', 'secondary', 'link'];

  onMounted(async () => {
    try {
      data.value = await graphQlHelper(homeQuery);
    } catch (error) {
      throw new Error(error);
    }
  });
</script>
