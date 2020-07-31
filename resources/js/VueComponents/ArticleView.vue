<template>
    <div class="container article-view">
        <header class="article-header">
            <div class="row article-title">
                <div class="col-12">
                    <h1 class="text-center">{{ article.title }}</h1>
                </div>
            </div>
            <div class="row article-header-details">
                <div class="col-7">
                    <div class="d-flex justify-content-start">
                        <div class="author-image">
                            <img :src="article.author.avatar" alt="author_avatar.png" class="rounded-circle">
                        </div>
                        <div class="author-details pl-2 mb-4 align-self-center">
                            <p class="author-name mb-1">@{{ article.author.name }}</p>
                            <p class="article-info text-secondary" style="position: absolute; width: 175px;">&#9672; {{ article.created_at }} | {{ article.est_time }} min</p>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="d-flex flex-column align-items-end">
                        <span class="category-link pt-2"><i class="fas fa-tags fa-md"></i> <a :href="article.category.href">{{ article.category.name }}</a></span>
                        <div class="article-social-share pl-2">
                            <div>
                                <a href="https://www.facebook.com/"><i class="fab fa-facebook-square fa-lg pr-1"></i></a>
                                <a href="https://www.facebook.com/"><i class="fab fa-twitter-square fa-lg"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="article-body">
            <section>
                <div class="loader">
                    <bounce-loader class="custom-class" :class="{ highIndex: loading }" :loading="loading" :color="loader.color" :size="loader.size" :margin="loader.margin"></bounce-loader>
                </div>
                <img v-if="article.main_image" :src="article.main_image" alt="main_image.jpg" class="article-main-image img-fluid mx-auto d-block">
                <div v-html="article.content"></div>
            </section>
        </div>
        <hr>
        <div class="article-bottom">
            <div class="article-tags">
                <h1>Tag-uri:</h1>
                <div class="float-left" v-for="tag in article.tags">
                    <kbd><a href='#'>{{ tag }}</a></kbd>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="article-suggestions" v-show="recommendedArticles.length">
                <hr>
                <h1>Articole care ar putea să te intereseze:</h1>
                <div class="row">
                    <div class="col-md-4" v-for="article in recommendedArticles">
                        <div class="card">
                            <a :href='/article/ + article.slug'>
                                <div class="article-image" :style="{ backgroundImage: 'url(/storage/articles/' + article.slug + '/' + article.main_image + ')' }"></div>
                            </a>
                            <div class="card-body">
                                <a :href='/article/ + article.slug'><h2 class="card-text">{{ article.title }}</h2></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <newsletter v-show="!loading"></newsletter>
    </div>
</template>

<script>
    import { BounceLoader } from '@saeris/vue-spinners';
    import postscribe from 'postscribe';
    import Newsletter from './Newsletter';

    export default {
        name: 'article-view',
        components: { BounceLoader, Newsletter },
        props: {
            slug: {
                type: String,
                required: true,
            },
        },
        data() {
            return {
                article: {
                    title: "",
                    slug: "",
                    content: "",
                    est_time: 1,
                    created_at: "",
                    main_image: "",
                    author: {
                        avatar: "../storage/avatars/user.png"
                    },
                    category: {},
                    tags: [],
                },
                recommendedArticles: {},
                loading: false,
                loader: {
                    color: "#16E8CA",
                    size: 200,
                    margin: 0,
                },
            }
        },
        mounted: function () {
            this.fetchArticle();
        },
        methods: {
            /**
             * Fetch the Article Data.
             */
            fetchArticle: function () {
                this.loading = true;
                let parameters = {
                    slug: this.slug
                };

                axios.post('/article/fetchArticleData', parameters)
                    .then((response) => {
                        if (response.data.success) {
                            let article = response.data.response;

                            this.article.title = article.title;
                            this.article.est_time = article.est_time;
                            this.article.created_at = article.created_at;
                            this.article.content = article.content;
                            this.article.tags = JSON.parse(article.tags);

                            if (article.main_image) {
                                this.article.main_image = "../storage/articles/" + this.slug + "/" + article.main_image;
                            } else {
                                this.article.main_image = null;
                            }

                            this.article.author = {
                                href: "authors/" + article.author_id,
                                name: article.author_username,
                                avatar: "../storage/avatars/" + article.author_avatar,
                            };
                            this.article.category = {
                                id:   article.category_id,
                                href: "categories/" + article.category_id,
                                name: article.category_name,
                            };

                            this.insertGists();
                            this.insertRelatedArticles();
                            this.loading = false;
                        } else {
                            this.toast('b-toaster-bottom-right', "danger", "Oops!", "Se pare ca a aparut o problema la incarcarea paginii. Te rog incearca din nou mai tarziu.");
                        }
                    })
                    .catch(function () {
                        this.toast('b-toaster-bottom-right', "danger", "Oops!", "Se pare ca a aparut o problema la incarcarea paginii. Te rog incearca din nou mai tarziu.");
                    }.bind(this));
            },
            /**
             * Insert the Gists after the Article Data is fetched.
             */
            insertGists: function () {
                window.addEventListener("load", () => {
                    let $gists = $("div[id^=gist]");

                    _.forEach($gists, function(gist) {
                        let id = $(gist).attr('id');
                        let src = $(gist).attr('data-src');

                        postscribe('#' + id, `<script src="` + src + `"><\/script>`);
                    });
                });
            },
            /**
             * Insert Related Articles from DB.
             */
            insertRelatedArticles: function () {
                let parameters = {
                    title:  this.article.title,
                    tags:   this.article.tags,
                    article_category: this.article.category.id
                };

                axios.post('/article/fetchRelatedArticles', parameters)
                    .then((response) => {
                        if (response.data.success) {
                            this.recommendedArticles = response.data.response;
                        }
                    })
                    .catch(function () {
                        this.toast('b-toaster-bottom-right', "danger", "Oops!", "Se pare ca a aparut o problema la incarcarea articolelor recomandate. Te rog incearca din nou mai tarziu.");
                    }.bind(this));
            },
            /**
             * Create display message using "toast" bootstrap-vue component.
             */
            toast: function (toaster, variant, title, message) {
                this.$bvToast.toast(message, {
                    title: title,
                    variant: variant,
                    toaster: toaster,
                    solid: true,
                    appendToast: true,
                })
            },
        }
    }
</script>

<style>

</style>
