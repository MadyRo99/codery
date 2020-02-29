<?php

Route::namespace('Articles')->group(function () {
    Route::get('/article/create', [
        'as'            =>  'articles.create',
        'uses'          =>  'ArticlesController@getCreateArticlePage',
        'middleware'    =>  'auth',
    ]);
    Route::post('/article/create/saveArticle', [
        'as'            =>  'articles.saveArticle',
        'uses'          =>  'ArticlesController@saveArticle',
        'middleware'    =>  'auth',
    ]);
    Route::post('/article/fetchArticleData', [
        'as'    =>  'articles.fetchArticleData',
        'uses'  =>  'ArticlesController@fetchArticleData',
    ]);
    Route::get('/article/create/getCategories', [
        'as'            =>  'articles.getCategories',
        'uses'          =>  'ArticlesController@getCategories',
        'middleware'    =>  'auth',
    ]);
    Route::get('/article/{slug}', [
        'as'    =>  'articles.index',
        'uses'  =>  'ArticlesController@index',
    ]);
});