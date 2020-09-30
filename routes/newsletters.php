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
});
