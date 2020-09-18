<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', [
    'as'            =>  'acasa',
    'uses'          =>  'PagesController@getHomePage',
]);

Route::get('/about', [
    'as'            =>  'about',
    'uses'          =>  'PagesController@getAboutPage',
]);

Route::post('/getAllArticles', [
    'as'            =>  'getAllArticles',
    'uses'          =>  'PagesController@getAllArticles',
]);

Route::post('/getArticles', [
    'as'            =>  'getArticles',
    'uses'          =>  'PagesController@getArticles',
]);

Route::get('/getAdminPanel', [
    'as'            =>  'getAdminPanel',
    'uses'          =>  'PagesController@getAdminPanel',
]);

// Authentication Routes
require ('auth.php');

// Authentication Routes
require ('articles.php');

require ('categories.php');
