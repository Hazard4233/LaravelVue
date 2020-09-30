<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'SpaController')->name('spa.register');


#API routes

Route::prefix('api')->middleware(['guest:api'])->group(function () {
    Route::post('/register', 'Auth\RegisterController@register');
});
