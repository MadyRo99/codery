<template>
    <div class="container home">
        <div class="loader">
            <bounce-loader class="custom-class" :class="{ highIndex: loading }" :loading="loading" :color="loader.color" :size="loader.size" :margin="loader.margin"></bounce-loader>
        </div>
        <br>
        <div class="row">
            <div class="col-12" style="padding: 0;">
                <div class="form-row">
                    <div class="form-group col-9 col-lg-10">
                        <input type="text" class="form-control searchPlaceholder" placeholder="Caută articol...">
                    </div>
                    <div class="form-group col-3 col-lg-2">
                        <input type="submit" class="form-control searchButton" value="Caută">
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-between">
            <div class="col-12 col-md-9 col-lg-10 article-list">
                <div class="row article" v-for="article in articles.data">
                    <div class="col-12">
                        <div class="row shadow article-border">
                            <a :href='/article/ + article.slug' class="col-12 col-md-6 col-lg-5 article-section" style="padding: 0;" v-if="article.main_image"><div class="article-image" :style="{ backgroundImage: 'url(/storage/articles/' + article.slug + '/' + article.main_image + ')' }"></div></a>
                            <div class="article-section" :class="{ 'col-12 col-md-6 col-lg-7' : article.main_image }">
                                <div class="row">
                                    <div class="col-10">
                                        <h1><a :href='/article/ + article.slug'>{{ article.title }}</a></h1>
                                    </div>
                                    <div class="col-2">
                                        <div class="row justify-content-end">
                                            <i class="far fa-bookmark bookmark"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="row article-info">
                                    <div class="col-12">
                                        <div class="float-left">
                                            <i class="far fa-calendar-alt"></i> <span> Mart 22, 2020</span>
                                        </div>
                                        <div class="float-right">
                                            <i class="fas fa-tags fa-md" style="padding-top: 5px;"></i> <span> JavaScript</span>
                                        </div>
                                    </div>
                                    <!--<div class="col-6">-->
                                        <!--<i class="far fa-calendar-alt"></i> <span> Mart 22, 2020</span>-->
                                    <!--</div>-->
                                    <!--<div class="col-6">-->
                                        <!--<div class="row justify-content-end">-->
                                            <!--<i class="fas fa-tags fa-md" style="padding-top: 5px;"></i> <span> JavaScript</span>-->
                                        <!--</div>-->
                                    <!--</div>-->
                                </div>
                                <!--TODO: data/categorie/tags/minute-->
                                <p>{{ article.content }}</p>
                                <h2><a :href='/article/ + article.slug'>Citește mai mult</a></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-none filters-list d-md-block col-md-3 col-lg-2">
                <div class="row">
                    <div class="col-12 shadow categories">
                        <h1>Categorii</h1>
                        <hr>
                        <p>JavaScript</p>
                        <hr>
                        <p>PHP</p>
                        <hr>
                        <p>HTML</p>
                        <hr>
                        <p>CSS</p>
                        <hr>
                        <p>Git</p>
                        <hr>
                        <p>Laravel</p>
                        <hr>
                        <p>VueJs</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 shadow tags">
                        <h1>Tag-uri</h1>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
        <pagination :pagination="articles"
                    @paginate="fetchArticles()"
                    offset="4">
        </pagination>
    </div>
</template>

<script>
    import { BounceLoader } from '@saeris/vue-spinners';
    import Pagination from './Pagination';

    export default {
        name: 'home',
        components: { BounceLoader, Pagination },
        data() {
            return {
                articles: {
                    total: 0,
                    per_page: 5,
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
            };
        },
        mounted: function () {
            this.fetchArticles();
        },
        methods: {
            fetchArticles: function() {
                this.loading = true;
                let getArticlesUrl = "/getArticles?page=" + this.articles.current_page;

                axios.get(getArticlesUrl)
                    .then((response) => {
                        this.articles = response.data;
                    }).catch((error) => {
                        console.log(error);
                    }).then(() => {
                        this.loading = false;
                    });
            },
        }
    };
</script>

<style>

</style>
