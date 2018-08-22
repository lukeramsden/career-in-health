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

Route::prefix('job-listing')
	 ->name('job-listing.')
	 ->group(function ()
	 {
		 Route::get('/', 'JobListingController@index')->name('index');
		 Route::get('/new', 'JobListingController@create')->name('create');
		 Route::post('/new', 'JobListingController@store')->name('store');

		 Route::get('/{jobListing}/view', 'JobListingController@showInternal')->name('show.internal');
		 Route::get('/{jobListing}/view/applications', 'JobListingController@showApplications')
			  ->name('show.applications');

		 Route::get('/{jobListing}/edit', 'JobListingController@edit')->name('edit');
		 Route::post('/{jobListing}/edit', 'JobListingController@update')->name('update');
		 Route::any('/{jobListing}/delete', 'JobListingController@destroy')->name('destroy');

		 Route::get('/{jobListing}', 'JobListingController@show')->name('show');

		 Route::prefix('application')
			  ->name('application.')
			  ->group(function ()
			  {
				  Route::get('/all', 'JobListingApplicationController@index')->name('index');

				  Route::get('/{jobListing}/create', 'JobListingApplicationController@create')->name('create');
				  Route::post('/{jobListing}/create', 'JobListingApplicationController@store')->name('store');

				  Route::post('/{application}/update', 'JobListingApplicationController@update')->name('update');
				  Route::get('/{application}', 'JobListingApplicationController@show')->name('show');
			  });
	 });

Route::prefix('address')
	 ->name('address.')
	 ->group(function ()
	 {
		 Route::get('/', 'AddressController@index')->name('index');
		 Route::get('/new', 'AddressController@create')->name('create');
		 Route::post('/new', 'AddressController@store')->name('store');

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

		 Route::get('/private-messages', 'PrivateMessageController@index')->name('private-message.index');
		 Route::post('/private-messages/send', 'PrivateMessageController@store')->name('private-message.store');
		 Route::get('/private-messages/{jobListing}', 'PrivateMessageController@showForJobListing')
			  ->name('private-message.show-employee');
		 Route::get('/private-messages/{jobListing}/{employee}', 'PrivateMessageController@showForJobListingAndEmployee')
			  ->name('private-message.show-company');
	 });

Route::prefix('employee')
	 ->name('employee.')
	 ->group(function ()
	 {
		 Route::get('/edit', 'EmployeeController@edit')->name('edit');
		 Route::post('/edit', 'EmployeeController@update')->name('update');
		 Route::get('/view/{employee}', 'EmployeeController@show')->name('show');
		 Route::get('/view/', 'EmployeeController@showMe')->name('show.me');
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
		 Route::get('/view/', 'CompanyController@showMe')->name('show.me');
		 Route::get('/applications/', 'CompanyController@showApplications')->name('show.applications');

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
			  });

		 Route::any('/{job-listing}/click', 'TrackingController@advertClickThrough')->name('job-listing.click');
	 });

Route::prefix('advertising')
	 ->name('advertising.')
	 ->group(function ()
	 {
		 Route::get('/registration/{advertiserInvite}/accept', 'Advertising\RegisterController@show')->name('accept-invite.show');
		 Route::post('/registration/{advertiserInvite}/accept', 'Advertising\RegisterController@store')->name('accept-invite');

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