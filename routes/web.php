<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'PhotoGalleryController@index');
Route::post('/', 'PhotoGalleryController@store');
Route::delete('/{id}', 'PhotoGalleryController@delete');
Route::post('/setting', 'PhotoGalleryController@setting');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/guest', 'Auth\LoginController@authenticate');
