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

Route::get('/', 'HoldingController@index');
Route::post('/', 'HoldingController@subscribe');

if (env('APP_ENV') == 'local') {

    Auth::routes();

    Route::get('/home', 'HomeController@index')->name('home');

    Route::prefix('/account')->group(function() {
        Route::get('/adverts', 'AdvertController@index')->name('adverts');
        Route::get('/advert', 'AdvertController@create')->name('advert_create');
        Route::post('/advert', 'AdvertController@store');

        Route::get('/advert/{advert}', 'AdvertController@edit')->name('advert_edit');
        Route::post('/advert/{advert}', 'AdvertController@update');

    });

}