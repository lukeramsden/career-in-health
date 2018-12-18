<?php

Route::get('/edit', 'EmployeeController@edit')->name('edit');
Route::post('/edit', 'EmployeeController@update')->name('update');
Route::get('/view/{employee}', 'EmployeeController@show')->name('show');
Route::get('/view/', 'EmployeeController@showMe')->name('show.me');

Route::any('/saved-job-listings', 'SavedJobListingController@index')
	 ->name('saved-job-listings');
Route::any('/saved-job-listings/get', 'SavedJobListingController@get')
	 ->name('saved-job-listings.get');
Route::any('/save-job-listing/{jobListing}', 'SavedJobListingController@add')
	 ->name('save-job-listing');
Route::any('/unsave-job-listing/{jobListing}', 'SavedJobListingController@remove')
	 ->name('unsave-job-listing');

Route::any('/next-onboard-step', function () {
  return redirect(Auth::user()->onboarding()->nextUnfinishedStep()->link);
})->name('next-onboard-step');
