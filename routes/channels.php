<?php

use App\Employee;
use App\JobListing;
use Illuminate\Support\Facades\Log;

/**
 * User Type Authentication
 */

Broadcast::channel('App.User.{id}', function ($user, $id) {
  /** @var \App\User $user */
  Log::debug('App.User.'.$id, [$user]);
  return $user->id === (int)$id;
});

Broadcast::channel('App.Employee.{id}', function ($user, $id) {
  /** @var \App\User $user */
  return $user->isEmployee() && (int)$user->userable->id === (int)$id;
});

Broadcast::channel('App.Employee.{id}', function ($user, $id) {
  /** @var \App\User $user */
  return $user->isEmployee() && (int)$user->userable->id === (int)$id;
});

Broadcast::channel('App.CompanyUser.{id}', function ($user, $id) {
  /** @var \App\User $user */
  return $user->isCompany() && (int)$user->userable->id === (int)$id;
});

Broadcast::channel('App.Advertiser.{id}', function ($user, $id) {
  /** @var \App\User $user */
  return $user->isAdvertiser() && (int)$user->userable->id === (int)$id;
});

Broadcast::channel('App.CompanyUser.{id}', function ($user, $id) {
  /** @var \App\User $user */
  return $user->isAdmin() && (int)$user->userable->id === (int)$id;
});

Broadcast::channel('App.Company.{id}', function ($user, $id) {
  /** @var \App\User $user */
  return $user->isValidCompany() && (int)$user->userable->company->id === (int)$id;
});

/**
 * Model type authentication
 */

Broadcast::channel('App.Listing.{listing}', function ($user, JobListing $listing) {
  /** @var \App\User $user */
  return $user->isValidCompany() && !is_null($listing) && (int)$user->userable->company->id === (int)$listing->company_id;
});

/**
 * Other Types of Authentication
 */

Broadcast::channel('App.PrivateMessage.Listing.{listing}.Employee.{employee}', function ($user, JobListing $listing, Employee $employee) {
  /** @var \App\User $user */

  if ($user->isEmployee())
	return (int)$user->userable_id === (int)$employee->id;

  if ($user->isValidCompany())
	return (int)$user->userable->company_id === (int)$listing->company_id;

  return false;
});