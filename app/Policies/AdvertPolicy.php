<?php

namespace App\Policies;

use App\Advertising\Advert;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;


class AdvertPolicy
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
	 * Determine if a user can create adverts
	 *
	 * @param User $user
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function create(User $user)
	{
		return $user->isAdvertiser();
	}

	/**
	 * Determine if a user can edit a given job-listing
	 *
	 * @param User   $user
	 * @param Advert $advert
	 *
	 * @return bool
	 */
	public function edit(User $user, Advert $advert)
	{
		return $user->isAdvertiser() && $advert->advertiser_id === $user->userable_id;
	}

	/**
	 * Determine if a user can be shown a given job-listing
	 *
	 * @param User   $user
	 * @param Advert $advert
	 *
	 * @return bool
	 */
	public function beShown(User $user, Advert $advert)
	{
		return $user->isEmployee() && $advert->active;
	}
}
