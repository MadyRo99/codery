<template>
    <div class="container home">
        <div class="loader">
            <bounce-loader class="custom-class" :class="{ highIndex: loading }" :loading="loading" :color="loader.color" :size="loader.size" :margin="loader.margin"></bounce-loader>
        </div>
        <br>
        <div class="row">
            <div class="col-12" style="padding: 0;">
                <div class="form-row">
                    <div class="form-group col-9 col-lg-10" style="padding-right: 0;">
                        <input type="search" class="form-control searchPlaceholder" v-model="searchInput" @keyup.enter="updateArticlesFiltered(null, true)" placeholder="Caută articol...">
                    </div>
                    <div class="form-group col-3 col-lg-2" style="padding-left: 0;">
                        <input type="submit" class="form-control searchButton" value="Caută" @click="updateArticlesFiltered(null, true)">
                    </div>
                </div>
            </div>
        </div>
        <div class="row filters-mobile">
            <div class="col-12" style="padding: 0;">
                <button class="form-control searchButton" style="border-radius: .25rem;" @click="mobileFiltersActive = !mobileFiltersActive" :class="{ buttonNoRadius: mobileFiltersActive }">Categorii</button>
                <slide-up-down class="filters-mobile-content" :active="mobileFiltersActive" :duration="500">
                    <div class="row">
                        <div class="col-12">
                            <div @click="updateArticlesFiltered(0)">
                                <p :class="{ selectedCategory: setCategory === 0 }" style="padding-top: 15px;">Toate</p>
                            </div>
                            <div v-for="category in categories" @click="updateArticlesFiltered(category.id)">
                                <hr>
                                <p :class="{ selectedCategory: setCategory === category.id }">{{ category.name }}</p>
                            </div>
                        </div>
                    </div>
                </slide-up-down>
            </div>
        </div>
        <div class="row justify-content-between">
            <div class="col-12 col-lg-10 article-list">
                <div class="row article" v-for="article in articles.data">
                    <div class="col-12">
                        <div class="row shadow article-border">
                            <a :href='/article/ + article.slug' class="col-12 col-md-6 col-lg-5 article-section" style="padding: 0;" v-if="article.main_image"><div class="article-image" :style="{ backgroundImage: 'url(/storage/articles/' + article.slug + '/' + article.main_image + ')' }"></div></a>
                            <div class="article-section" :class="{ 'col-12 col-md-6 col-lg-7' : article.main_image }">
                                <div class="row">
                                    <div class="col-12">
                                        <h1><a :href='/article/ + article.slug'>{{ article.title }}</a></h1>
                                    </div>
<!--                                    <div class="col-2">-->
<!--                                        <div class="row justify-content-end">-->
<!--                                            <i class="far fa-bookmark bookmark"></i>-->
<!--                                        </div>-->
<!--                                    </div>-->
                                </div>
                                <div class="row article-info">
                                    <div class="col-12">
                                        <div class="float-left">
                                            <i class="far fa-calendar-alt"></i> <span class="pl-1">{{ dateAbbreviation(article.created_at) }}</span>
                                        </div>
                                        <div class="float-right">
                                            <i class="fas fa-tags fa-md" style="padding-top: 5px; padding-left: 5px;"></i> <span> {{ article.name }}</span>
                                        </div>
                                    </div>
                                </div>
                                <p>{{ article.description || article.content }}</p>
                                <h2><a :href='/article/ + article.slug'>Citește mai mult</a></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-none filters-list d-lg-block col-lg-2">
                <div class="row">
                    <div class="col-12 shadow categories">
                        <h1>Categorii</h1>
                        <div @click="updateArticlesFiltered(0)">
                            <hr style="">
                            <p :class="{ selectedCategory: setCategory === 0 }">Toate</p>
                        </div>
                        <div v-for="category in categories" @click="updateArticlesFiltered(category.id)">
                            <hr>
                            <p :class="{ selectedCategory: setCategory === category.id }">{{ category.name }}</p>
                        </div>
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
    import SlideUpDown from 'vue-slide-up-down';
    import Pagination from './Pagination';

    export default {
        name: 'home',
        components: { BounceLoader, SlideUpDown, Pagination },
        props: {
            search: {
                type: String,
                required: true,
            },
        },
        data() {
            return {
                searchArticle: this.search,
                searchInput: this.search,
                articles: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                categories: {},
                setCategory: 0,
                loading: false,
                loader: {
                    color: "#16E8CA",
                    size: 200,
                    margin: 0,
                },
                mobileFiltersActive: false,
            };
        },
        mounted: function () {
            this.fetchCategories();
            this.fetchArticles();
        },
        methods: {
            /**
             * Fetch the categories available to be displayed on the first page.
             */
            fetchCategories: function() {
                this.loading = true;
                let getCategoriesUrl = "/getCategories";

                axios.get(getCategoriesUrl)
                    .then((response) => {
                        this.categories = response.data.response;
                    }).catch((error) => {
                        console.log(error);
                    }).then(() => {
                        this.loading = false;
                    });
            },
            /**
             * Fetch the Articles to be displayed on the first page.
             */
            fetchArticles: function() {
                this.loading = true;
                let getArticlesUrl = "/getArticles?page=" + this.articles.current_page;
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
            /**
             * Update the Articles Displayed and the Pagination for them accroding to the filtering.
             */
            updateArticlesFiltered: function(categoryId, changedSearch = null) {
                if (categoryId !== null) {
                    this.setCategory = categoryId;
                }
                if (changedSearch) {
                    this.searchArticle = this.searchInput;
                }

                this.articles.current_page = 1;
                this.fetchArticles();
            },
            /**
             * Format the date of the article.
             */
            dateAbbreviation: function(date) {
                let dateTimestamp = new Date(date);

                let day   = dateTimestamp.getDate();
                let month = dateTimestamp.getMonth();
                let year  = dateTimestamp.getFullYear();

                switch (month) {
                    case 0:
                        month = 'Ian';
                        break;
                    case 1:
                        month = 'Feb';
                        break;
                    case 2:
                        month = 'Mart';
                        break;
                    case 3:
                        month = 'Apr';
                        break;
                    case 4:
                        month = 'Mai';
                        break;
                    case 5:
                        month = 'Iun';
                        break;
                    case 6:
                        month = 'Iul';
                        break;
                    case 7:
                        month = 'Aug';
                        break;
                    case 8:
                        month = 'Sept';
                        break;
                    case 9:
                        month = 'Oct';
                        break;
                    case 10:
                        month = 'Nov';
                        break;
                    case 11:
                        month = 'Dec';
                        break;
                }

                return month + ' ' + day + ', ' + year;
            }
        }
    };
</script>

<style>

</style>
