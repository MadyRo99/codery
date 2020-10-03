<?php

Route::namespace('Auth')->group(function () {
//  Authentication Routes...
    Route::get('/login', 'LoginController@showLoginForm')->name('login');
    Route::post('/login', 'LoginController@login');
    Route::post('/logout', 'LoginController@logout')->name('logout');

//    Registration Routes...
//    Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
//    Route::post('register', 'RegisterController@register');

//    Password Reset Routes...
//    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
//    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail');
//    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm');
//    Route::post('password/reset', 'ResetPasswordController@reset');

});
