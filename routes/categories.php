<?php

Route::namespace('Categories')->group(function () {
    Route::get('/getCategories', [
        'as'            =>  'getCategories',
        'uses'          =>  'CategoriesController@getCategories',
    ]);
    Route::get('/categories', [
        'as'            =>  'categories.index',
        'uses'          =>  'CategoriesController@getCategoriesList',
    ]);
    Route::post('/categories/create', [
        'as'            =>  'categories.create',
        'uses'          =>  'CategoriesController@createCategory',
    ]);
    Route::post('/categories/delete/{id}', [
        'as'            =>  'categories.delete',
        'uses'          =>  'CategoriesController@deleteCategory',
    ]);
    Route::post('/categories/edit/{id}', [
        'as'            =>  'categories.edit',
        'uses'          =>  'CategoriesController@editCategory',
    ]);
});
