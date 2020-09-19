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

// Basic Routes

Route::get('/', [
    'as'            =>  'home',
    'uses'          =>  'PagesController@getHomePage',
]);

Route::get('/about', [
    'as'            =>  'about',
    'uses'          =>  'PagesController@getAboutPage',
]);

Route::post('/getArticles', [
    'as'            =>  'getArticles',
    'uses'          =>  'PagesController@getArticles',
]);

Route::post('/getAllArticles', [
    'as'            =>  'getAllArticles',
    'uses'          =>  'PagesController@getAllArticles',
    'middleware'    =>  'auth-admin'
]);

Route::get('/getAdminPanel', [
    'as'            =>  'getAdminPanel',
    'uses'          =>  'PagesController@getAdminPanel',
    'middleware'    =>  'auth-admin'
]);

// Authentication Routes
require ('auth.php');

// Articles Routes
require ('articles.php');

// Categories Routes
require ('categories.php');
