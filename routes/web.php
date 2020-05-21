<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'PhotoGalleryController@index');
Route::post('/', 'PhotoGalleryController@store');