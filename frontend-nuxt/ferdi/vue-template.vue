<template>
    <div :class="name">
        finally rendered
    </div>
</template>

<script>
    export default {
        props: {
            headline: {
                type: String,
                default: () => '',
            },
        },
        data() {
            return {
                name: 'c-<%= moduleName.replace(' - ', '_').charAt(0) %><%= moduleName.replace('-', '_').slice(1) %>',
            };
        },
        computed: {

        },
        watch: {},
        mounted() { },
        created() { },
        methods: {

        },
    };
</script>