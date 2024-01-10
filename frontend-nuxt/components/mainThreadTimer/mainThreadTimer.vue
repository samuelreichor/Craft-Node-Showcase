<template>
  <div :class="[compDefaults.name, compDefaults.classes.root]">
    <div :class="compDefaults.classes.counter">{{ counter }}</div>
  </div>
</template>

<script setup lang="ts">
  const compDefaults = {
    name: 'c-mainThreadTimer',
    classes: {
      root: 'fixed left-4 bottom-4',
      counter:
        'bg-black flex justify-center items-center text-sm text-white p-2 min-w-[5rem]',
    },
  };

  const counter = ref(0);
  let intervalId;

  const startTimer = () => {
    intervalId = setInterval(() => {
      counter.value = (counter.value + 1) % 1000;
    }, 500);
  };

  onMounted(() => {
    startTimer();
  });

  onUnmounted(() => {
    clearInterval(intervalId);
  });
</script>
