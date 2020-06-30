require('./bootstrap');

import Vue from 'vue';
import { BootstrapVue } from 'bootstrap-vue';
import 'bootstrap-vue/dist/bootstrap-vue.css';
Vue.use(BootstrapVue);

import Home from './VueComponents/Home';
import About from './VueComponents/About';
import ArticleView from './VueComponents/ArticleView';
import CreateArticle from './VueComponents/CreateArticle';
import EditArticle from './VueComponents/EditArticle';

import { BounceLoader } from '@saeris/vue-spinners';
import Pagination from './VueComponents/Pagination';
import MultipleFileUploader from './VueComponents/MultipleFileUploader';
import Newsletter from './VueComponents/Newsletter';

const vue = new Vue({
    el: '#app',
    components: {
        'home'                   : Home,
        'about'                  : About,
        'article-view'           : ArticleView,
        'create-article'         : CreateArticle,
        'edit-article'           : EditArticle,
        'bounce-loader'          : BounceLoader,
        'pagination'             : Pagination,
        'multiple-file-uploader' : MultipleFileUploader,
        'newsletter'             : Newsletter,
    },
});
