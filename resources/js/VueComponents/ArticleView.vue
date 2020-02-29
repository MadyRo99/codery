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
                <img src="../../../public/images/coding.jpg" alt="coding.jpg" class="article-image img-fluid mx-auto d-block">
                <div v-html="article.content"></div>
            </section>
        </div>
        <newsletter></newsletter>
    </div>
</template>

<script>
    import Newsletter from './Newsletter';

    export default {
        name: 'article',
        components: { Newsletter },
        props: ['slug'],
        data() {
            return {
                article: {
                    title: "",
                    slug: "",
                    content: "",
                    est_time: 1,
                    image: '',
                    created_at: "",
                    author: {},
                    category: {},
                },
            }
        },
        created: function () {
            this.fetchArticle();
        },
        methods: {
            fetchArticle: function () {
                let parameters = {
                    slug: this.slug
                };

                axios.post('/article/fetchArticleData', parameters)
                    .then((response) => {
                        if (response.data.success) {
                            let article = response.data.response;
                            console.log(article);
                            this.article.title = article.title;
                            this.article.est_time = article.est_time;
                            this.article.created_at = article.created_at;
                            this.article.content = article.content;
                            this.article.author = {
                                href: "authors/" + article.author_id,
                                name: article.author_username,
                                avatar: "../storage/avatars/" + article.author_avatar,
                            };
                            this.article.category = {
                                href: "categories/" + article.category_id,
                                name: article.category_name,
                            };
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