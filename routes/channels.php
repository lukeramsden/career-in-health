<?php

/**
 * User Type Authentication
 */

Broadcast::channel('App.User.{id}', function ($user, $id)
{
	/** @var \App\User $user */
	return (int)$user->id === (int)$id;
});

Broadcast::channel('App.Employee.{id}', function ($user, $id)
{
	/** @var \App\User $user */
	return $user->isEmployee() && (int)$user->userable->id === (int)$id;
});

Broadcast::channel('App.CompanyUser.{id}', function ($user, $id)
{
	/** @var \App\User $user */
	return $user->isCompany() && (int)$user->userable->id === (int)$id;
});

Broadcast::channel('App.Advertiser.{id}', function ($user, $id)
{
	/** @var \App\User $user */
	return $user->isAdvertiser() && (int)$user->userable->id === (int)$id;
});

Broadcast::channel('App.CompanyUser.{id}', function ($user, $id)
{
	/** @var \App\User $user */
	return $user->isAdmin() && (int)$user->userable->id === (int)$id;
});

Broadcast::channel('App.Company.{id}', function ($user, $id)
{
	/** @var \App\User $user */
	return $user->isValidCompany() && (int)$user->userable->company->id === (int)$id;
});

/**
 * Other Types of Authentication
 */

Broadcast::channel('App.PrivateMessage.Listing.{jobListingId}.Employee.{employeeId}', function ($user, $jobListingId, $employeeId)
{
	/** @var \App\User $user */

	try {
		/** @var \App\JobListing $jobListing */
		$jobListing = \App\JobListing::findOrFail($jobListingId);
		/** @var \App\Employee $employee */
		$employee   = \App\Employee::findOrFail($employeeId);
	} catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
		Log::error($e->getMessage());
		return false;
	}

	if($user->isEmployee())
		return (int)$user->userable_id === (int)$employee->id;

	if($user->isValidCompany())
		return (int)$user->userable->company_id === (int)$jobListing->company_id;

	return false;
});