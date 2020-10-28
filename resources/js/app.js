require('./bootstrap');
require('./VueMixins');

import Vue from 'vue';
import { BootstrapVue } from 'bootstrap-vue';
import 'bootstrap-vue/dist/bootstrap-vue.css';
Vue.use(BootstrapVue);

import Home from './VueComponents/Home';
import About from './VueComponents/About';
import Donate from './VueComponents/Donate';
import ArticleView from './VueComponents/ArticleView';
import AdminPanel from './VueComponents/AdminPanel';
import ListCategories from './VueComponents/ListCategories';
import CreateArticle from './VueComponents/CreateArticle';
import EditArticle from './VueComponents/EditArticle';
import ArticlesNewsletter from './VueComponents/ArticlesNewsletter';

import { BounceLoader } from '@saeris/vue-spinners';
import SlideUpDown from 'vue-slide-up-down';
import Pagination from './VueComponents/Pagination';
import MultipleFileUploader from './VueComponents/MultipleFileUploader';
import Newsletter from './VueComponents/Newsletter';

const vue = new Vue({
    el: '#app',
    components: {
        'home'                   : Home,
        'about'                  : About,
        'donate'                 : Donate,
        'article-view'           : ArticleView,
        'admin-panel'            : AdminPanel,
        'list-categories'        : ListCategories,
        'articles-newsletter'    : ArticlesNewsletter,
        'create-article'         : CreateArticle,
        'edit-article'           : EditArticle,
        'bounce-loader'          : BounceLoader,
        'slide-up-down'          : SlideUpDown,
        'pagination'             : Pagination,
        'multiple-file-uploader' : MultipleFileUploader,
        'newsletter'             : Newsletter
    },
});
