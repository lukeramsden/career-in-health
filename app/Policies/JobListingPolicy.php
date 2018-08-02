<?php

namespace App\Policies;

use App\JobListing;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobListingPolicy
{
	use HandlesAuthorization;

	/**
	 * Create a new policy instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Determine if the given user is valid for any of the defined policies
	 *
	 * @param User $user
	 * @param      $ability
	 *
	 * @return bool|null
	 * @throws \Exception
	 */
	public function before($user, $ability)
	{
		return null;
	}

	/**
	 * Determine if the given job listing can be viewed by a user
	 *
	 * @param User       $user
	 * @param JobListing $jobListing
	 *
	 * @throws \Exception
	 */
	public function view(User $user, JobListing $jobListing)
	{
		return $jobListing->isPublished()
			|| ($user->isValidCompany()
				&& $jobListing->company_id === $user->userable->company_id);
	}

	/**
	 * Determine if the given user can create job listings
	 *
	 * @param User $user
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function create(User $user)
	{
		return $user->isValidCompany() &&
			$user->userable->company->addresses()->exists();
	}

	/**
	 * Determine if the given job listing can be updated by a user
	 *
	 * @param User       $user
	 * @param JobListing $jobListing
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function update(User $user, JobListing $jobListing)
	{
		return $user->isValidCompany()
			&& $jobListing->company_id === $user->userable->company_id;
	}

	/**
	 * Determine if the given job listing can be deleted by a user
	 *
	 * @param User       $user
	 * @param JobListing $jobListing
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function delete(User $user, JobListing $jobListing)
	{
		return $user->isValidCompany() &&
			$jobListing->company_id === $user->userable->company_id;
	}
}
