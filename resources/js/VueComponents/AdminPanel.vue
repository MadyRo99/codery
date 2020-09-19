<template>
    <div class="admin-panel">
        <div class="container">
            <h2>Panou Administrare</h2>
            <div class="alert alert-info" role="alert">
                Administrează și analizează articolele sau categoriile disponibile.
            </div>
            <div class="admin-actions">
                <a href="/categories"><button type="button" class="btn btn-outline-link">Categorii</button></a>
                <a href="/article/create"><button type="button" class="btn btn-outline-link">Adaugă Articol</button></a>
                <form class="float-md-right" action="/logout" method="POST">
                    <input type="hidden" name="_token" :value="csrf">
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>

            <div class="col-12 article-list" style="padding: 0;">
                <div class="loader">
                    <bounce-loader class="custom-class" :class="{ highIndex: loading }" :loading="loading" :color="loader.color" :size="loader.size" :margin="loader.margin"></bounce-loader>
                </div>
                <div class="col-12 col-lg-6 article float-left" v-for="article in articles">
                    <div class="row shadow article-border">
                        <a :href='/article/ + article.slug' class="col-12 col-md-6 col-lg-5 article-section" style="padding: 0;" v-if="article.main_image"><div class="article-image" :style="{ backgroundImage: 'url(/storage/articles/' + article.slug + '/' + article.main_image + ')' }"></div></a>
                        <div class="article-section" :class="{ 'col-12 col-md-6 col-lg-7' : article.main_image, 'col-12' : !article.main_image }">
                            <div class="row">
                                <div class="col-12">
                                    <h1><a :href='/article/ + article.slug'>{{ article.title }}</a></h1>
                                </div>
                            </div>
                            <div class="row article-info">
                                <div class="col-12">
                                    <div class="float-left">
                                        <i class="far fa-calendar-alt"></i> <span class="pl-1">{{ dateAbbreviation(article.created_at) }}</span>
                                    </div>
                                    <div class="float-right">
                                        <i class="fas fa-tags fa-md" style="padding-top: 5px; padding-left: 5px;"></i> <span> {{ article.name }}</span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="row" style="padding-bottom: 7.5px;">
                                <div class="col-12">
                                    <div v-if="article.status">
                                        <i class="fas fa-eye" style="padding-top: 5px; color: #16E8CA;"></i> <span>Articol Public</span>
                                    </div>
                                    <div v-if="!article.status">
                                        <i class="fa fa-eye-slash" aria-hidden="true" style="padding-top: 5px; color: #993FFF;"></i> <span>Articol Privat</span>
                                    </div>
                                </div>
                            </div>
                            <a href="#" class="float-left"><button type="button" class="btn btn-info">Statistici</button></a>
                            <a :href="'/article/edit/' + article.slug" class="float-right"><button type="button" class="btn btn-primary">Editează</button></a>
                            <div class="clearfix"></div>
                            <p>{{ article.description }}</p>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</template>

<script>
    import { BounceLoader } from '@saeris/vue-spinners';

    export default {
        name: 'admin-panel',
        components: { BounceLoader },
        data() {
            return {
                articles: {},
                loading: false,
                loader: {
                    color: "#16E8CA",
                    size: 200,
                    margin: 0,
                },
            }
        },
        computed: {
            csrf() {
                return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            }
        },
        mounted: function () {
            this.fetchArticles();
        },
        methods: {
            /**
             * Fetch the Articles to be displayed on the Admin Panel.
             */
            fetchArticles: function() {
                this.loading = true;
                let getArticlesUrl = "/getAllArticles";
                let params = {
                    categoryId: this.setCategory,
                    search: this.searchArticle
                };

                axios.post(getArticlesUrl, params)
                    .then((response) => {
                        this.articles = response.data;
                    }).catch((error) => {
                    console.log(error);
                }).then(() => {
                    this.loading = false;
                });
            },
        }
    }
</script>

<style>

</style>
