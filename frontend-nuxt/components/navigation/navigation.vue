<template>
    <ul class="flex gap-5" v-if="data">
        <li v-if="data.navigationNodes" v-for="node in data.navigationNodes">
            <NuxtLink :to="node.url" class="">
                {{ node.title }}
                <ul v-if="node.children">
                    <li v-for="child in node.children" class="">
                        <NuxtLink :to="child.url" >
                            {{ child.title }}
                        </NuxtLink>
                    </li>
                </ul>
            </NuxtLink>
        </li>
    </ul>
</template>

<script setup lang="ts">
    const { data } = await useAsyncGql({
        operation: 'navigation',
    });
</script>
