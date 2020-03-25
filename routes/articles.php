<?php

Route::namespace('Articles')->group(function () {
    Route::get('/article/create', [
        'as'            =>  'articles.create',
        'uses'          =>  'ArticlesController@getCreateArticlePage',
        'middleware'    =>  'auth-author',
    ]);
    Route::get('/article/create/getCategories', [
        'as'            =>  'articles.getCategories',
        'uses'          =>  'ArticlesController@getCategories',
        'middleware'    =>  'auth-author',
    ]);
    Route::post('/article/create/saveArticle', [
        'as'            =>  'articles.saveArticle',
        'uses'          =>  'ArticlesController@saveArticle',
        'middleware'    =>  'auth-author',
    ]);
    Route::get('/article/edit/{slug}', [
        'as'            =>  'articles.edit',
        'uses'          =>  'ArticlesController@getUpdateArticlePage',
        'middleware'    =>  'auth-author',
    ]);
    Route::post('/article/edit/{slug}/updateArticle', [
        'as'            =>  'articles.edit.updateArticle',
        'uses'          =>  'ArticlesController@updateArticle',
        'middleware'    =>  'auth-author',
    ]);
    Route::post('/article/edit/{slug}/deleteImage', [
        'as'            =>  'articles.edit.deleteImage',
        'uses'          =>  'ArticlesController@deleteImage',
        'middleware'    =>  'auth-author',
    ]);
    Route::post('/article/delete/{slug}', [
        'as'            =>  'articles.delete.deleteArticle',
        'uses'          =>  'ArticlesController@deleteArticle',
        'middleware'    =>  'auth-author',
    ]);
    Route::post('/article/create/saveArticleImages', [
        'as'            =>  'articles.saveArticleImages',
        'uses'          =>  'ArticlesController@saveArticleImages',
        'middleware'    =>  'auth-author',
    ]);
    Route::post('/article/fetchArticleData', [
        'as'    =>  'articles.fetchArticleData',
        'uses'  =>  'ArticlesController@fetchArticleData',
    ]);
    Route::post('/article/fetchUpdateArticleData', [
        'as'    =>  'articles.fetchUpdateArticleData',
        'uses'  =>  'ArticlesController@fetchUpdateArticleData',
    ]);
    Route::get('/article/{slug}', [
        'as'    =>  'articles.index',
        'uses'  =>  'ArticlesController@index',
    ]);
});