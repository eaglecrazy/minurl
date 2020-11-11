<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('url');
});


Route::post('/geturl', 'UrlController@geturl')->name('getUrl');
