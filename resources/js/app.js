require('./bootstrap');

import Vue from 'vue';
import ArticleView from './VueComponents/ArticleView';
import Newsletter from './VueComponents/Newsletter';
import CreateArticle from './VueComponents/CreateArticle';

const vue = new Vue({
    el: '#app',
    components: {
        'article-view'  : ArticleView,
        'newsletter'    : Newsletter,
        'create-article': CreateArticle,
    }
});
