<?php

Route::view('/', 'home')->name('home');
Route::view('/pricing', 'pricing')->name('pricing');

Route::get('/search', 'SearchController@search')->name('search');

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout')->name('logout.get');
Route::get('/register/verify/{confirmationCode}', 'Auth\RegisterController@confirm')->name('confirm-email');
Route::any('/register/verify/resend/{user}', 'Auth\RegisterController@resend')->name('confirm-email-resend');
Route::get('/register/verify', 'Auth\RegisterController@prompt')->name('prompt-confirm-email');

Route::get('/dashboard', 'DashController@index')->name('dashboard');
Route::post('/dashboard', 'DashController@get')->name('dashboard.get');

Route::prefix('advert')
    ->name('advert.')
    ->group(function() {
        Route::get('/', 'AdvertController@index')->name('index');
        Route::get('/new', 'AdvertController@create')->name('create');
        Route::post('/new', 'AdvertController@store')->name('store');

        Route::get('/{advert}/view', 'AdvertController@showInternal')->name('show.internal');
        Route::get('/{advert}/view/applications', 'AdvertController@showApplications')->name('show.applications');

        Route::get('/{advert}/edit', 'AdvertController@edit')->name('edit');
        Route::post('/{advert}/edit', 'AdvertController@update')->name('update');
        Route::any('/{advert}/delete', 'AdvertController@destroy')->name('destroy');

        Route::get('/{advert}', 'AdvertController@show')->name('show');

        Route::prefix('application')
            ->name('application.')
            ->group(function() {
                Route::get('/all', 'AdvertApplicationController@index')->name('index');

                Route::get('/{advert}/create', 'AdvertApplicationController@create')->name('create');
                Route::post('/{advert}/create', 'AdvertApplicationController@store')->name('store');

                Route::post('/{application}/update', 'AdvertApplicationController@update')->name('update');
                Route::get('/{application}', 'AdvertApplicationController@show')->name('show');
            });
    });

Route::prefix('address')
    ->name('address.')
    ->group(function() {
        Route::get('/', 'AddressController@index')->name('index');
        Route::get('/new', 'AddressController@create')->name('create');
        Route::post('/new', 'AddressController@store')->name('store');

        Route::get('/{address}/edit', 'AddressController@edit')->name('edit');
        Route::post('/{address}/edit', 'AddressController@update')->name('update');
        Route::any('/{address}/delete', 'AddressController@destroy')->name('destroy');
    });

Route::get('/plans', 'SubscriptionController@index')->name('plans');

Route::prefix('account')
    ->name('account.')
    ->group(function() {
        Route::get('/email', 'UserController@showEmail')->name('manage.email');
        Route::post('/email', 'UserController@updateEmail')->name('manage.email');
        Route::get('/password', 'UserController@showPassword')->name('manage.password');
        Route::post('/password', 'UserController@updatePassword')->name('manage.password');

        Route::get('/private-messages', 'PrivateMessageController@index')->name('private-message.index');
        Route::get('/private-messages/sent', 'PrivateMessageController@indexSent')->name('private-message.index.sent');
        Route::get('/private-message/{message}', 'PrivateMessageController@show')->name('private-message.show');
        Route::any('/private-message/{message}/mark-as-read', 'PrivateMessageController@markAsRead')->name('private-message.mark-as-read');
        Route::any('/private-message/{message}/mark-as-unread', 'PrivateMessageController@markAsUnread')->name('private-message.mark-as-unread');
    });

Route::prefix('profile')
    ->name('profile.')
    ->group(function () {
        Route::get("/", "ProfileController@showMe")->name('show.me');
        Route::get("/edit", "ProfileController@edit")->name('edit');
        Route::post("/edit", "ProfileController@update")->name('update');
        Route::get("/{user}", "ProfileController@show")->name('show');
    });

Route::prefix('cv')
    ->name('cv.')
    ->group(function () {
        Route::resource('education', 'Cv\CvEducationController', ['only' => [
            'store', 'update', 'destroy'
        ]]);

        Route::resource('workExperience', 'Cv\CvWorkExperienceController', ['only' => [
            'store', 'update', 'destroy'
        ]]);

        Route::resource('certifications', 'Cv\CvCertsController', ['only' => [
            'store', 'update', 'destroy'
        ]]);

        Route::put('preferences', 'Cv\CvPreferencesController@update')->name('preferences.update');

        Route::view('/', 'employee.cv.edit')
            ->middleware(['auth', 'only.employee'])
            ->name('builder');

        Route::get('/pdf/view', 'PersonnelFileController@view')->name('pdf.view');
        Route::get('/pdf/download', 'PersonnelFileController@download')->name('pdf.download');
    });

Route::prefix('company')
    ->name('company.')
    ->group(function () {
        Route::get("/", "CompanyProfileController@showMe")->name('show.me');
        Route::post("/edit", "CompanyProfileController@update")->name('update');
        Route::get("/edit", "CompanyProfileController@edit")->name('edit');
        Route::get("/{company}", "CompanyProfileController@show")->name('show');
    });

Route::prefix('tracking')
    ->name('tracking.')
    ->group(function() {
        Route::prefix('advert')
            ->name('advert.')
            ->group(function() {
                Route::get('/{advert}/search/click', function(\App\Advert $advert) {
                    $advert->increment('search_clicks');
                    session()->flash('clickThrough', 'search');
                    return redirect()->action('AdvertController@show', [$advert]);
                })->name('search.click');

                Route::get('/{advert}/recommended/click', function(\App\Advert $advert) {
                    $advert->increment('recommended_clicks');
                    session()->flash('clickThrough', 'recommended');
                    return redirect()->action('AdvertController@show', [$advert]);
                })->name('recommended.click');
            });
    });