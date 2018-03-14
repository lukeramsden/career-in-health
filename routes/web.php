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

    Auth::routes();

    Route::get('/home', 'HomeController@index')->name('home');

    Route::prefix('/account')->group(function() {
        Route::get('/adverts', 'AdvertController@index')->name('adverts');
        Route::get('/advert', 'AdvertController@create')->name('advert_create');
        Route::post('/advert', 'AdvertController@store');

        Route::get('/advert/{advert}', 'AdvertController@edit')->name('advert_edit');
        Route::post('/advert/{advert}', 'AdvertController@update');


        Route::get('/address', 'AddressController@create')->name('address_create');
        Route::post('/address', 'AddressController@store');


        Route::get('/payment', 'SubscriptionController@payment');
        Route::post('/payment', 'SubscriptionController@makePayment');
    });

    Route::get('/test', function() {
        $p = new App\Models\SubscriptionPlan();
        $p->getPlansFromStripe();

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

}