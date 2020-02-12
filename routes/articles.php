<?php

Route::namespace('Articles')->group(function () {
    Route::get('/article', 'ArticlesController@index')->name('articles.index');
});