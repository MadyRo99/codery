<template>
    <div class="loader">
        <bounce-loader class="custom-class" :class="{ highIndex: loading }" :loading="loading" :color="loader.color" :size="loader.size" :margin="loader.margin"></bounce-loader>
    </div>
</template>

<script>
    import { BounceLoader } from '@saeris/vue-spinners';

    export default {
        name: 'home',
        components: { BounceLoader },
        data() {
            return {
                articles: [],
                loading: false,
                loader: {
                    color: "#16E8CA",
                    size: 200,
                    margin: 0,
                },
            };
        },
        mounted: function () {
            this.fetchArticles();
        },
        methods: {
            fetchArticles: function() {

            },
        }
    };
</script>

<style>

</style>
