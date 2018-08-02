<?php

namespace App\Policies;

use App\CompanyUser;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyUserPolicy
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
	 * @param $user
	 * @param $ability
	 *
	 * @return bool|null
	 * @throws \Exception
	 */
	public function before($user, $ability)
	{
		/** @var User $user */
		if (!$user->isValidCompany())
			return false;

		return null;
	}

	/**
	 * Determine if user can view user management page
	 *
	 * @param User $user
	 *
	 * @return bool
	 */
	public function view(User $user)
	{
		/** @var CompanyUser $userable */
		return ($userable = $user->userable)->ownsCompany()
			|| $userable->permission_level === 'manager';
	}

	/**
	 * Determine if user can invite other users to the company
	 *
	 * @param User $user
	 *
	 * @return bool
	 */
	public function inviteUser(User $user)
	{
		return self::view($user);
	}

	/**
	 * Determine if user can change a given users permission level
	 *
	 * @param User        $user
	 * @param CompanyUser $companyUser
	 *
	 * @return bool
	 */
	public function changePermissionLevel(User $user, CompanyUser $companyUser)
	{
		return $user->userable->ownsCompany();
	}


	/**
	 * Determine if user can activate a given user
	 *
	 * @param User        $user
	 * @param CompanyUser $companyUser
	 *
	 * @return bool
	 */
	public function activateUser(User $user, CompanyUser $companyUser)
	{
		/** @var CompanyUser $userable */
		$userable = $user->userable;
		return $userable->ownsCompany() || $userable->permission_level === 'manager';
	}

	/**
	 * Determine if user can deactivate a given user
	 *
	 * @param User        $user
	 * @param CompanyUser $companyUser
	 *
	 * @return bool
	 */
	public function deactivateUser(User $user, CompanyUser $companyUser)
	{
		/** @var CompanyUser $userable */
		$userable = $user->userable;
		return $userable->ownsCompany() || $userable->hasPermsOver($companyUser);
	}

	/**
	 * Determine if user can change owner
	 *
	 * @param User $user
	 *
	 * @return bool
	 */
	public function changeOwner(User $user)
	{
		return $user->userable->ownsCompany();
	}
}
