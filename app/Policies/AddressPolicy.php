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
   * Determine if the given address can be viewed by a user
   *
   * @param User    $user
   * @param Address $address
   *
   * @return bool
   * @throws \Exception
   */
  public function view(User $user, Address $address)
  {
	return true;
  }

  /**
   * Determine if a user can create addresses
   *
   * @param User $user
   *
   * @return bool
   * @throws \Exception
   */
  public function create(User $user)
  {
	// TODO: check that billing allows for more addresses
	return $user->isValidCompany();
  }

  /**
   * Determine if the given address can be updated by a user
   *
   * @param User    $user
   * @param Address $address
   *
   * @return bool
   * @throws \Exception
   */
  public function update(User $user, Address $address)
  {
	return $user->isValidCompany()
	  && $address->company_id === $user->userable->company_id;
  }

  /**
   * Determine if the given address can be deleted by a user
   *
   * @param User    $user
   * @param Address $address
   *
   * @return bool
   * @throws \Exception
   */
  public function delete(User $user, Address $address)
  {
	return $user->isValidCompany() &&
	  $address->company_id === $user->userable->company_id;
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
