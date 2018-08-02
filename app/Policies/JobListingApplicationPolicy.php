<?php

namespace App\Policies;

use App\JobListingApplication;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobListingApplicationPolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view the application.
	 *
	 * @param  \App\User                  $user
	 * @param  \App\JobListingApplication $application
	 *
	 * @return bool
	 */
	public function view(User $user, JobListingApplication $application)
	{
		return false;
	}

	/**
	 * Determine whether the user can create applications.
	 *
	 * @param  \App\User $user
	 *
	 * @return bool
	 */
	public function create(User $user)
	{
		return false;
	}

	/**
	 * Determine whether the user can update the application.
	 *
	 * @param  \App\User                  $user
	 * @param  \App\JobListingApplication $application
	 *
	 * @return bool
	 */
	public function update(User $user, JobListingApplication $application)
	{
		return false;
	}

	/**
	 * Determine whether the user can delete the application.
	 *
	 * @param  \App\User                  $user
	 * @param  \App\JobListingApplication $application
	 *
	 * @return bool
	 */
	public function delete(User $user, JobListingApplication $application)
	{
		return false;
	}
}
