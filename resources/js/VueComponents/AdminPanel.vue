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
                                        <i class="far fa-calendar-alt"></i> <span class="pl-1">{{ article.created_at.substring(0,10) }}</span>
                                    </div>
                                    <div class="float-right">
                                        <i class="fas fa-tags fa-md" style="padding-top: 5px; padding-left: 5px;"></i> <span> {{ article.name }}</span>
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
jh
<script>
    import { BounceLoader } from '@saeris/vue-spinners';

    export default {
        name: 'admin-panel',
        components: { BounceLoader },
        data() {
            return {
                articles: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                loading: false,
                loader: {
                    color: "#16E8CA",
                    size: 200,
                    margin: 0,
                },
            }
        },
        mounted: function () {
            this.fetchArticles();
        },
        methods: {
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
