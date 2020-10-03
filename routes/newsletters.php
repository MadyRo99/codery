<?php

Route::namespace('Newsletters')->group(function () {
    Route::post('/joinNewsletter', [
        'as'            =>  'joinNewsletter',
        'uses'          =>  'NewslettersController@joinNewsletter',
    ]);
    Route::get('/confirmNewsletter/{token}', [
        'as'            =>  'confirmNewsletter',
        'uses'          =>  'NewslettersController@confirmNewsletter',
    ]);
    Route::get('/deleteNewsletter/{token}', [
        'as'            =>  'deleteNewsletter',
        'uses'          =>  'NewslettersController@deleteNewsletter',
    ]);
    Route::get('/articlesNewsletter/create', [
        'as'            =>  'articlesNewsletter.create',
        'uses'          =>  'NewslettersController@getArticlesNewsletter',
    ]);
    Route::get('/getArticlesBasicInfo', [
        'as'            =>  'getArticlesBasicInfo',
        'uses'          =>  'NewslettersController@getArticlesBasicInfo',
    ]);
    Route::post('/sendNewsletter', [
        'as'            =>  'sendNewsletter',
        'uses'          =>  'NewslettersController@sendNewsletter',
    ]);
});
