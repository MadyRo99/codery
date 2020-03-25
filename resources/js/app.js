require('./bootstrap');

import Vue from 'vue';
import { BootstrapVue } from 'bootstrap-vue';
import 'bootstrap-vue/dist/bootstrap-vue.css';
Vue.use(BootstrapVue);

import ArticleView from './VueComponents/ArticleView';
import CreateArticle from './VueComponents/CreateArticle';
import EditArticle from './VueComponents/EditArticle';

import { BounceLoader } from '@saeris/vue-spinners';
import MultipleFileUploader from './VueComponents/MultipleFileUploader';
import Newsletter from './VueComponents/Newsletter';

const vue = new Vue({
    el: '#app',
    components: {
        'article-view'           : ArticleView,
        'create-article'         : CreateArticle,
        'edit-article'           : EditArticle,
        'bounce-loader'          : BounceLoader,
        'multiple-file-uploader' : MultipleFileUploader,
        'newsletter'             : Newsletter,
    }
});
