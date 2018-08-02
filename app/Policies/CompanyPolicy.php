<?php

namespace App\Policies;

use App\Company;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
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
	 * Determine if a given company can be updated by a user
	 *
	 * @param User    $user
	 * @param Company $company
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function update(User $user, Company $company)
	{
		return $user->isValidCompany()
			&& $user->userable->company_id === $company->id;
	}
}
