<template>
    <a  v-if="btnText"
        :class="buttonClasses" 
        :href="btnLink"
        :target="computedTarget">
       {{btnText}}
    </a>
</template>

<script>

    export default {
        components: {},
        props: {
            btnText: {
                type: String,
                default: () => '',
            },
            btnLink: {
                type: String,
                default: () => '',
            },
            variant: {
                type: String,
                default: () => 'primary',
            },
            btnTarget: {
                type: Boolean,
                default: () => true,
            }
        },
        computed: {
            buttonClasses() {
                const variants = [
                    { name: 'primary', class: 'c-button-primary font-medium border-2 transition-all duration-200 border-primary hover:bg-transparent hover:text-black bg-primary rounded-full text-white pb-3 pt-2.5 px-6 cursor-pointer inline-block !leading-none tracking-wide relative no-underline' },
                    { name: 'secondary', class: 'c-button-secondary border-2 transition-all duration-200 border-primary bg-transparent text-black hover:bg-primary rounded-full hover:text-white pb-3 pt-2.5 px-6 cursor-pointer inline-block  !leading-none tracking-wide relative no-underline' },
                ];
                const selectedVariant = variants.find((v) => v.name === this.variant);
                return selectedVariant ? selectedVariant.class : '';
            },
            computedTarget() {
                return this.btnTarget ? '_blank' : '';
            }
        },
    };
</script>