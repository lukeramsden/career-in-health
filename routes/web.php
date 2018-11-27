<?php /** @noinspection PhpParamsInspection */

Route::view('/', 'home')->name('home');
Route::view('/pricing', 'pricing')->name('pricing');

Route::get('/search', 'SearchController@show')->name('search');
Route::post('/search', 'SearchController@get')->name('search.get');

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout')->name('logout.get');
Route::get('/register/verify/{confirmationCode}', 'Auth\RegisterController@confirm')->name('confirm-email');
Route::any('/register/verify/resend/{user}', 'Auth\RegisterController@resend')->name('confirm-email-resend');
Route::get('/register/verify', 'Auth\RegisterController@prompt')->name('prompt-confirm-email');

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
Route::post('/dashboard', 'DashboardController@get')->name('dashboard.get');

Route::prefix('job-listing')
	 ->name('job-listing.')
	 ->group(function ()
	 {
		 Route::get('/index', 'JobListingController@index')->name('index');
		 Route::post('/index', 'JobListingController@indexGet')->name('index.get');
		 Route::get('/new', 'JobListingController@create')->name('create');
		 Route::post('/new', 'JobListingController@store')->name('store');

		 Route::any('/{jobListing}/get', 'JobListingController@get')->name('get');

		 Route::get('/{jobListing}/edit', 'JobListingController@edit')->name('edit');
		 Route::post('/{jobListing}/edit', 'JobListingController@update')->name('update');
		 Route::any('/{jobListing}/delete', 'JobListingController@destroy')->name('destroy');
		 Route::any('/{jobListing}/open', 'JobListingController@open')->name('open');
		 Route::any('/{jobListing}/close', 'JobListingController@close')->name('close');

		 Route::get('/{jobListing}', 'JobListingController@show')->name('show');

		 Route::get('/{jobListing}/view/applications', 'JobListingController@showApplications')
			  ->name('view-applications');

		 Route::prefix('application')
			  ->name('application.')
			  ->group(function ()
			  {
				  Route::get('/all', 'JobListingApplicationController@index')->name('index');

				  Route::get('/{jobListing}/create', 'JobListingApplicationController@create')->name('create');
				  Route::post('/{jobListing}/create', 'JobListingApplicationController@store')->name('store');

				  Route::post('/{application}/update', 'JobListingApplicationController@update')->name('update');
				  Route::get('/{application}/edit', 'JobListingApplicationController@show')->name('show');
			  });

		 Route::any('/application/{application}/redirect', 'JobListingApplicationController@permalink')->name('application.permalink');
		 Route::get('/application/{application}', 'JobListingController@showApplication')->name('company.application.show');
	 });

Route::prefix('address')
	 ->name('address.')
	 ->group(function ()
	 {
		 Route::get('/', 'AddressController@index')->name('index');
		 Route::get('/new', 'AddressController@create')->name('create');
		 Route::post('/new', 'AddressController@store')->name('store');

	   Route::any('/{address}/get', 'AddressController@get')->name('get');


		 Route::get('/{address}/edit', 'AddressController@edit')->name('edit');
		 Route::post('/{address}/edit', 'AddressController@update')->name('update');
		 Route::any('/{address}/delete', 'AddressController@destroy')->name('destroy');
		 Route::get('/{address}', 'AddressController@show')->name('show');

		 Route::post('/{address}/image/new', 'AddressController@addImage')->name('image.store');
	 });

Route::prefix('account')
	 ->name('account.')
	 ->group(function ()
	 {
		 Route::get('/email', 'UserController@showEmail')->name('manage.email');
		 Route::post('/email', 'UserController@updateEmail')->name('manage.email');
		 Route::get('/password', 'UserController@showPassword')->name('manage.password');
		 Route::post('/password', 'UserController@updatePassword')->name('manage.password');
		 Route::get('/notification-preferences', 'UserController@editNotificationPreferences')->name('manage.notification-preferences');
		 Route::post('/notification-preferences', 'UserController@updateNotificationPreferences')->name('manage.notification-preferences');

		 Route::get('/private-messages', 'PrivateMessageController@index')->name('private-message.index');
		 Route::post('/private-messages/send', 'PrivateMessageController@store')->name('private-message.store');

		 Route::any('/private-messages/{jobListing}', 'PrivateMessageController@showForJobListing')
			  ->name('private-message.show-employee');
		 Route::any('/private-messages/{jobListing}/{employee}', 'PrivateMessageController@showForJobListingAndEmployee')
			  ->name('private-message.show-company');

		 Route::any('/private-messages/mark-all-as-read/{jobListing}/{employee?}', 'PrivateMessageController@markAllAsRead')
			  ->name('private-message.mark-all-as-read');

	 });

Route::prefix('employee')
	 ->name('employee.')
	 ->group(function ()
	 {
		 Route::get('/edit', 'EmployeeController@edit')->name('edit');
		 Route::post('/edit', 'EmployeeController@update')->name('update');
		 Route::get('/view/{employee}', 'EmployeeController@show')->name('show');
		 Route::get('/view/', 'EmployeeController@showMe')->name('show.me');

		 Route::any('/saved-job-listings', 'SavedJobListingController@index')->name('saved-job-listings');
		 Route::any('/save-job-listing/{jobListing}', 'SavedJobListingController@add')->name('save-job-listing');
		 Route::any('/unsave-job-listing/{jobListing}', 'SavedJobListingController@remove')->name('unsave-job-listing');

		 Route::any('/next-onboard-step', function ()
		 {
			 return redirect(Auth::user()->onboarding()->nextUnfinishedStep()->link);
		 })->name('next-onboard-step');
	 });

Route::prefix('company')
	 ->name('company.')
	 ->group(function ()
	 {
		 Route::get('/new', 'CompanyController@create')->name('create');
		 Route::post('/new', 'CompanyController@store')->name('store');
		 Route::get('/edit', 'CompanyController@edit')->name('edit');
		 Route::post('/edit', 'CompanyController@update')->name('update');
		 Route::get('/view/{company}', 'CompanyController@show')->name('show');
		 Route::get('/view', 'CompanyController@showMe')->name('show.me');
		 Route::get('/applications', 'CompanyController@showApplications')->name('application.index');
		 Route::post('/applications', 'CompanyController@getApplications')->name('application.index.get');

		 Route::any('/get-addresses', 'CompanyController@getAddresses')->name('get-addresses');

		 Route::prefix('manage-users')
			  ->name('manage-users.')
			  ->group(function ()
			  {
				  Route::get('/', 'CompanyUserManagementController@show')
					   ->name('show');
				  Route::post('/invite', 'CompanyUserManagementController@inviteUser')
					   ->name('invite');
				  Route::any('/invite/{invite}/cancel', 'CompanyUserManagementController@cancelInvite')
					   ->name('invite.cancel');
				  Route::any('/invite/{invite}/remind', 'CompanyUserManagementController@remindInvite')
					   ->name('invite.remind');

				  Route::any('/{companyUser}/update-permission-level', 'CompanyUserManagementController@updatePermissionForUser')
					   ->name('update-permission-level');
				  Route::any('{companyUser}/make-owner', 'CompanyUserManagementController@makeOwner')
					   ->name('make-owner');

				  Route::any('/{companyUser}/activate', 'CompanyUserManagementController@activateUser')
					   ->name('activate-user');
				  Route::any('{companyUser}/deactivate', 'CompanyUserManagementController@deactivateUser')
					   ->name('deactivate-user');
			  });
	 });

Route::prefix('company-user')
	 ->name('company-user.')
	 ->group(function ()
	 {
		 Route::get('/edit', 'CompanyUserController@edit')->name('edit');
		 Route::post('/edit', 'CompanyUserController@update')->name('update');
		 Route::get('/view/{companyUser}', 'CompanyUserController@show')->name('show');
		 Route::get('/view/', 'CompanyUserController@showMe')->name('show.me');
	 });

Route::prefix('cv')
	 ->name('cv.')
	 ->group(function ()
	 {
		 Route::resource('education', 'Cv\CvEducationController', [
			 'only' => [
				 'store', 'update', 'destroy',
			 ]]);

		 Route::resource('workExperience', 'Cv\CvWorkExperienceController', [
			 'only' => [
				 'store', 'update', 'destroy',
			 ]]);

		 Route::resource('certifications', 'Cv\CvCertsController', [
			 'only' => [
				 'store', 'update', 'destroy',
			 ]]);

		 Route::put('preferences', 'Cv\CvPreferencesController@update')->name('preferences.update');

		 Route::view('/', 'employee.cv.edit')
			  ->middleware(['auth', 'user-type:employee'])
			  ->name('builder');

		 Route::get('/pdf/view', 'PersonnelFileController@view')->name('pdf.view');
		 Route::get('/pdf/download', 'PersonnelFileController@download')->name('pdf.download');
	 });

Route::get('/accept-invite/{code}', 'CompanyUserInviteController@show')->name('accept-invite.show');
Route::post('/accept-invite/{invite}', 'CompanyUserInviteController@accept')->name('accept-invite.accept');

Route::prefix('media')
	 ->name('media.')
	 ->group(function ()
	 {
		 Route::any('/delete/{media}', 'MediaController@destroy')->name('destroy');
	 });

Route::prefix('tracking')
	 ->name('tracking.')
	 ->group(function ()
	 {
		 Route::prefix('job-listing')
			  ->name('job-listing.')
			  ->group(function ()
			  {
				  Route::get('/{jobListing}/search/click', function (\App\JobListing $jobListing)
				  {
					  $jobListing->increment('search_clicks');
					  session()->flash('clickThrough', 'search');
					  return redirect()->action('JobListingController@show', [$jobListing]);
				  })->name('search.click');

				  Route::get('/{jobListing}/recommended/click', function (\App\JobListing $jobListing)
				  {
					  $jobListing->increment('recommended_clicks');
					  session()->flash('clickThrough', 'recommended');
					  return redirect()->action('JobListingController@show', [$jobListing]);
				  })->name('recommended.click');

				  Route::any('/{job-listing}/click', 'TrackingController@advertClickThrough')
					   ->name('click');
			  });

		 Route::prefix('advertising')
			  ->name('advert.')
			  ->group(function ()
			  {
				  Route::any('/homepage/{homePageAdvert}/click', 'TrackingController@homePageAdvertClick')
					   ->name('homepage.click');
			  });
	 });

Route::prefix('advertising')
	 ->name('advertising.')
	 ->group(function ()
	 {
		 Route::get('/registration/{advertiserInvite}/accept', 'Advertising\RegisterController@show')
			  ->name('accept-invite.show');
		 Route::post('/registration/{advertiserInvite}/accept', 'Advertising\RegisterController@store')
			  ->name('accept-invite');

		 Route::get('/create', 'Advertising\AdvertController@create')->name('create');
		 Route::post('/create', 'Advertising\AdvertController@store')->name('store');

		 Route::get('/{advert}/edit', 'Advertising\AdvertController@edit')->name('edit');
		 Route::post('/{advert}/edit', 'Advertising\AdvertController@update')->name('update');

		 Route::any('/{advert}/delete', 'Advertising\AdvertController@destroy')->name('destroy');

		 Route::get('/', 'Advertising\AdvertController@index')->name('index');
	 });

Route::prefix('admin')
	 ->name('admin.')
	 ->group(function ()
	 {
		 Route::prefix('manage-advertisers')
			  ->name('manage-advertisers.')
			  ->group(function ()
			  {
				  Route::get('/', 'Admin\AdvertiserManagementController@show')
					   ->name('show');
				  Route::post('/invite', 'Admin\AdvertiserManagementController@inviteUser')
					   ->name('invite');
				  Route::any('/invite/{invite}/cancel', 'Admin\AdvertiserManagementController@cancelInvite')
					   ->name('invite.cancel');
				  Route::any('/invite/{invite}/remind', 'Admin\AdvertiserManagementController@remindInvite')
					   ->name('invite.remind');

				  Route::any('/{advertiser}/activate', 'Admin\AdvertiserManagementController@activateUser')
					   ->name('activate-user');
				  Route::any('{advertiser}/deactivate', 'Admin\AdvertiserManagementController@deactivateUser')
					   ->name('deactivate-user');
			  });
	 });

Route::prefix('notifications')
	 ->name('notifications.')
	 ->group(function ()
	 {
		 Route::get('/all', 'NotificationController@index')->name('index');
		 Route::any('/all/mark/read', 'NotificationController@markAllAsRead')->name('mark-all-as-read');
		 Route::any('/all/mark/unread', 'NotificationController@markAllAsUnread')->name('mark-all-as-unread');
		 Route::any('/all/delete/all', 'NotificationController@deleteAll')->name('delete-all');
		 Route::any('/all/delete/read', 'NotificationController@deleteAllRead')->name('delete-all-read');

		 Route::any('/click-through/{notification}/{prop?}', 'NotificationController@clickThrough')->name('click-through');

		 Route::any('/{notification}/mark-as-read', 'NotificationController@markAsRead')->name('mark-as-read');
		 Route::any('/{notification}/mark-as-unread', 'NotificationController@markAsUnread')->name('mark-as-unread');
		 Route::any('/{notification}/delete', 'NotificationController@delete')->name('delete');
	 });

/*
 * Used for loading data in to Vue components
 * Maybe there's a compile time or cached way to do this? No idea
 */
Route::prefix('get-all')
	->name('get-all-')
	->group(function ()
	{
		Route::any('job-roles', function() {
			return response()->json(\App\JobRole::all(), 200);
		})->name('job-roles');

		Route::any('locations', function() {
			return response()->json(array_values(\App\Location::getAllLocations()), 200);
		})->name('locations');

		Route::any('listing-settings', function() {
			return response()->json(\App\JobListing::$settings, 200);
		})->name('listing-settings');

		Route::any('listing-types', function() {
			return response()->json(\App\JobListing::$types, 200);
		})->name('listing-types');
	});

