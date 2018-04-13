<?php

Route::get('/', 'HoldingController@index');
Route::get('/subscribe-thank-you', 'HoldingController@subscribeThankYou');
Route::post('/', 'HoldingController@subscribe');

if (env('APP_ENV') == 'local')
{
    Route::view('/', 'home')->name('home');
    Route::view('/pricing', 'pricing')->name('pricing');

    Route::view('/test', 'employee.cv.edit');

    Route::get('/search', 'SearchController@search')->name('search');
    Route::get('/advert/{advert}', 'AdvertController@show')->name('advert.show');

    Auth::routes();
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout.get');

    Route::get('/dashboard', 'DashController@index')->name('dashboard');

    Route::prefix('account')->group(function()
    {
        Route::prefix('advert')
            ->name('advert.')
            ->group(function()
        {
            Route::get('/', 'AdvertController@index')->name('index');
            Route::get('/new', 'AdvertController@create')->name('create');
            Route::post('/new', 'AdvertController@store')->name('store');

            Route::get('/{advert}/view', 'AdvertController@show_internal')->name('show.internal');

            Route::get('/{advert}/edit', 'AdvertController@edit')->name('edit');
            Route::post('/{advert}/edit', 'AdvertController@update')->name('update');

            Route::prefix('application')
                ->name('application.')
                ->group(function() {
                    Route::get('/all', 'AdvertApplicationController@index')->name('index');

                    Route::post('/{application}/update', 'AdvertApplicationController@update')->name('update');
                    Route::get('/{advert}', 'AdvertApplicationController@create')->name('create');
                    Route::post('/{advert}', 'AdvertApplicationController@store')->name('store');
                });
        });

        Route::prefix('address')
            ->name('address.')
            ->group(function() {
                Route::get('/address', 'AddressController@create')->name('create');
                Route::post('/address', 'AddressController@store')->name('store');
            });

        Route::get('/plans', 'SubscriptionController@index')->name('plans');
    });

    Route::prefix('profile')
        ->name('profile.')
        ->group(function ()
    {
        Route::get("/", "ProfileController@show_me")->name('show.me');
        Route::get("/edit", "ProfileController@edit")->name('edit');
        Route::post("/edit", "ProfileController@update")->name('update');
        Route::get("/{user}", "ProfileController@show")->name('show');
    });

    Route::prefix('cv')
        ->name('cv.')
        ->group(function ()
    {
        Route::resource('education', 'Cv\CvEducationController', ['only' => [
            'store', 'update', 'destroy'
        ]]);
    });

    Route::prefix('company')
        ->name('company.')
        ->group(function ()
    {
        Route::get("/", "CompanyProfileController@show_me")->name('show.me');
        Route::post("/edit", "CompanyProfileController@update")->name('update');
        Route::get("/edit", "CompanyProfileController@edit")->name('edit');
        Route::get("/{company}", "CompanyProfileController@show")->name('show');
    });
}