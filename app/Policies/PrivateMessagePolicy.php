<?php

namespace App\Policies;

use App\PrivateMessage;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrivateMessagePolicy
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
	 * Determine if user can use the private messaging system
	 *
	 * @param User $user
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function sendMessages(User $user)
	{
		return $user->isEmployee() || $user->isValidCompany();
	}

	/**
	 * Determine if user can change read status for a given private message
	 *
	 * @param User           $user
	 * @param PrivateMessage $privateMessage
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function changeReadStatus(User $user, PrivateMessage $privateMessage)
	{
		return $privateMessage->wasSentTo($user);
	}
}
