<template>
    <div class="container article-view">
        <header class="article-header">
            <div class="row article-title">
                <div class="col-12">
                    <h1 class="text-center">{{ article.title }}</h1>
                </div>
            </div>
            <div class="row article-header-details">
                <div class="col-6">
                    <div class="d-flex justify-content-start">
                        <div class="author-image pr-1">
                            <a :href="article.author.href"><img :src="article.author.avatar" alt="author_avatar.png" class="rounded-circle"></a>
                        </div>
                        <div class="author-details pl-2 align-self-center">
                            <a :href="article.author.href"><p class="author-name mb-1">@{{ article.author.name }}</p></a>
                            <p class="article-info text-secondary mb-0">{{ article.created_at }} | {{ article.est_time }} min</p>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column align-items-end">
                        <span class="category-link py-2"><i class="fas fa-tags fa-md"></i> <a :href="article.category.href">{{ article.category.name }}</a></span>
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
                <img v-if="article.main_image" :src="article.main_image" alt="main_image.jpg" class="article-main-image img-fluid mx-auto d-block">
                <div v-html="article.content"></div>
            </section>
        </div>
        <newsletter></newsletter>
    </div>
</template>

<script>
    import postscribe from 'postscribe';
    import Newsletter from './Newsletter';

    export default {
        name: 'article-view',
        components: { Newsletter },
        props: ['slug'],
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
                },
            }
        },
        mounted: function () {
            this.fetchArticle();
        },
        methods: {
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
             * Fetch the Article Data.
             */
            fetchArticle: function () {
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
                                href: "categories/" + article.category_id,
                                name: article.category_name,
                            };

                            this.insertGists();
                        } else {
                            console.log(response.data.response);
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            }
        }
    }
</script>

<style>

</style>