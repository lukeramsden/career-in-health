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
Route::get('/subscribe-thank-you', 'HoldingController@subscribeThankYou');
Route::post('/', 'HoldingController@subscribe');

if (env('APP_ENV') == 'local')
{
    Route::view('/', 'welcome')->name('welcome');
    Route::view('/pricing', 'pricing')->name('pricing');

    Route::get('/search', 'SearchController@search')->name('search');
    Route::get('/advert/{advert}', 'AdvertController@show')->name('advert.show');

    Auth::routes();

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
                ->name('application')
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
        Route::post("/edit", "ProfileController@update")->name('update');
        Route::get("/edit", "ProfileController@edit")->name('edit');
        Route::get("/{user}", "ProfileController@show")->name('show');

        Route::prefix('work-experience')
            ->name('work-experience')
            ->group(function ()
        {
            Route::get('/edit', 'ProfileWorkExperienceController@edit')->name('edit');
            Route::post('/create', 'ProfileWorkExperienceController@store')->name('store');
            Route::get('/{profileWorkExperience}/edit', 'ProfileWorkExperienceController@edit_single')->name('edit.single');
            Route::post('/{profileWorkExperience}/edit', 'ProfileWorkExperienceController@update')->name('update');
            Route::get('/{profileWorkExperience}/destroy', 'ProfileWorkExperienceController@destroy')->name('destroy');
        });

        Route::prefix('references')
            ->name('references.')
            ->group(function ()
        {
            Route::get('/edit', 'ReferenceController@edit')->name('edit');
            Route::post('/create', 'ReferenceController@store')->name('store');
            Route::get('/{reference}/edit', 'ReferenceController@edit_single')->name('edit.single');
            Route::post('/{reference}/edit', 'ReferenceController@update')->name('update');
            Route::get('/{reference}/destroy', 'ReferenceController@destroy')->name('destroy');
        });

        Route::prefix('certifications')
            ->name('certifications.')
            ->group(function ()
        {
            Route::get('/edit', 'CertificationController@edit')->name('edit');
            Route::post('/create', 'CertificationController@store')->name('store');
            Route::get('/{certification}/edit', 'CertificationController@edit_single')->name('edit.single');
            Route::post('/{certification}/edit', 'CertificationController@update')->name('update');
            Route::get('/{certification}/destroy', 'CertificationController@destroy')->name('destroy');
            Route::get('/{certification}/download', 'CertificationController@download')->name('download');
        });
    });

    Route::prefix('cv-builder')
        ->name('cv-builder.')
        ->group(function ()
    {
        Route::get('/1_profile', 'CVBuilderController@step1_show')->name('profile');
        Route::post('/1_profile', 'CVBuilderController@step1_save')->name('profile.save');

        Route::get('/2_work-experience', 'CVBuilderController@step2_show')->name('work-experience');
        Route::post('/2_work-experience', 'CVBuilderController@step2_save')->name('work-experience.save');

        Route::get('/3_references', 'CVBuilderController@step3_show')->name('references');
        Route::post('/3_references', 'CVBuilderController@step3_save')->name('references.save');

        Route::get('/4_certifications', 'CVBuilderController@step4_show')->name('certifications');
        Route::post('/4_certifications', 'CVBuilderController@step4_save')->name('certifications.save');
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