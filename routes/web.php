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

if (env('APP_ENV') == 'local') {

    Route::get('/', function() {
        return view('welcome');
    });

    Route::get('/search', 'SearchController@search')->name('search');

    Route::get('/advert/{advert}', 'AdvertController@show')->name('advert.show');

    Auth::routes();

    Route::get('/home', 'HomeController@index')->name('home');

    Route::group(['middleware' => ['auth']], function () {
        Route::prefix('account')->group(function() {
            Route::prefix('advert')->group(function() {
                Route::get('/', 'AdvertController@index')->name('adverts');
                Route::get('/new', 'AdvertController@create')->name('advert_create');
                Route::post('/', 'AdvertController@store')->name('advert_store');

                Route::get('/{advert}/view', 'AdvertController@show_internal')->name('advert_show_internal');

                Route::get('/{advert}/edit', 'AdvertController@edit')->name('advert_edit');
                Route::post('/{advert}/edit', 'AdvertController@update');

                Route::get('/{advert}/apply', 'AdvertApplicationController@create')->name('advert_apply');
                Route::post('/{advert}/apply', 'AdvertApplicationController@store')->name('advert_store');
            });

            Route::post('/application/{application}/update', 'AdvertApplicationController@update')->name('advert-application.update');

            Route::get('/address', 'AddressController@create')->name('address_create');
            Route::post('/address', 'AddressController@store');

            Route::get('/plans', 'SubscriptionController@index')->name('plans');

            Route::get('/payment/{plan}', 'SubscriptionController@payment');
            Route::post('/payment/{plan}', 'SubscriptionController@makePayment');
        });

        Route::prefix('profile')->group(function () {
                Route::get("/", "ProfileController@show")->name('profile');
                Route::get("/edit", "ProfileController@edit")->name('profile.edit');
                Route::post("/edit", "ProfileController@update")->name('profile.update');

                Route::prefix('work-experience')->group(function () {
                    Route::get('/edit', 'ProfileWorkExperienceController@edit')->name('profile.work.edit');
                    Route::post('/create', 'ProfileWorkExperienceController@store')->name('profile.work.store');
                    Route::get('/{profileWorkExperience}/edit', 'ProfileWorkExperienceController@edit_single')->name('profile.work.edit_single');
                    Route::post('/{profileWorkExperience}/edit', 'ProfileWorkExperienceController@update')->name('profile.work.update');
                    Route::get('/{profileWorkExperience}/destroy', 'ProfileWorkExperienceController@destroy')->name('profile.work.destroy');
                });

                Route::prefix('references')->group(function () {
                    Route::get('/edit', 'ReferenceController@edit')->name('profile.references.edit');
                    Route::post('/create', 'ReferenceController@store')->name('profile.references.store');
                    Route::get('/{reference}/edit', 'ReferenceController@edit_single')->name('profile.references.edit_single');
                    Route::post('/{reference}/edit', 'ReferenceController@update')->name('profile.references.update');
                    Route::get('/{reference}/destroy', 'ReferenceController@destroy')->name('profile.references.destroy');
                });

                Route::prefix('certifications')->group(function () {
                    Route::get('/edit', 'CertificationController@edit')->name('profile.certifications.edit');
                    Route::post('/create', 'CertificationController@store')->name('profile.certifications.store');
                    Route::get('/{certification}/edit', 'CertificationController@edit_single')->name('profile.certifications.edit_single');
                    Route::post('/{certification}/edit', 'CertificationController@update')->name('profile.certifications.update');
                    Route::get('/{certification}/destroy', 'CertificationController@destroy')->name('profile.certifications.destroy');
                    Route::get('/{certification}/download', 'CertificationController@download')->name('profile.certifications.download');
                });
            });

        Route::prefix('cv-builder')->group(function () {
            Route::get('/1_profile', 'CVBuilderController@step1_show')->name('cv-builder.profile');
            Route::post('/1_profile', 'CVBuilderController@step1_save');

            Route::get('/2_work-experience', 'CVBuilderController@step2_show')->name('cv-builder.work-experience');
            Route::post('/2_work-experience', 'CVBuilderController@step2_save');

            Route::get('/3_references', 'CVBuilderController@step3_show')->name('cv-builder.references');
            Route::post('/3_references', 'CVBuilderController@step3_save');

            Route::get('/4_certifications', 'CVBuilderController@step4_show')->name('cv-builder.certifications');
            Route::post('/4_certifications', 'CVBuilderController@step4_save');
        });

        Route::get('/personnel', 'PersonnelFileController@generate');
        Route::get('/test', function() {
            //     $p = new App\Models\SubscriptionPlan();
            //     $p->getPlansFromStripe();

            //     $user = App\User::find(1);

            //     Stripe\Stripe::setApiKey(env('STRIPE_KEY'));

            //     $token = Stripe\Token::create([
            //         'card' => [
            //             'address_line1' => '44 leicester rd',
            //             'address_zip' => 'fy1 4hl',
            //             'number' => '4000056655665556',
            //             'exp_month' => '12',
            //             'exp_year' => '2019',
            //             'cvc' => '123',
            //             'currency' => 'GBP',
            //         ]
            //     ]);

            //     $user->newSubscription('Standard Packages', 'starter')
            //         ->create($token->id, [
            //             'email' => $user->email
            //         ]);
        });
    });
}