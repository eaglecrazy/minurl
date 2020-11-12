<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'UrlController@home')->name('home');;
Route::post('/geturl', 'UrlController@geturl')->name('getUrl');
Route::get('/{link}', 'UrlController@redirect')->name('redirect');
