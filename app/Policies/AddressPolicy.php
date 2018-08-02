<?php

namespace App\Policies;

use App\Address;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
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
	 * Determine if the given address can be used in a job listing by a user
	 *
	 * @param User    $user
	 * @param Address $address
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function useInJobListing(User $user, Address $address)
	{
		return $user->isValidCompany()
			&& $address->company_id === $user->userable->company_id;
	}
}
