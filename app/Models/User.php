<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
	use Notifiable;
	use \Calebporzio\Onboard\GetsOnboarded;

	protected $fillable = [
		'email',
	];

	protected $hidden = [
		'password', 'remember_token',
	];

	protected static function boot()
	{
		parent::boot();

		static::deleting(function (User $user)
		{
			$user->userable()->delete();
		});
	}

	public function userable()
	{
		return $this->morphTo();
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public function hasCreatedAddress()
	{
		static $b = null;
		if (is_null($b))
			$b = $this->isValidCompany() && $this->userable->company->addresses()->count() > 0;
		return $b;
	}

	/**
	 * Checks if is company user AND if company is valid
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function isValidCompany()
	{
		static $b = null;
		if (is_null($b))
			$b = $this->isCompany() && $this->userable->company()->exists();
		return $b;
	}

	public function isCompany()
	{
		return $this->userable instanceof CompanyUser;
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public function hasCreatedAdvert()
	{
		static $b = null;
		if (is_null($b))
			// has_created_first_advert is here because we don't want to return to onboarding
			// if the company deletes all the adverts they have created
			$b = $this->isValidCompany() && $this->userable->company->has_created_first_advert;
		return $b;
	}

	public function isAdmin()
	{
		return $this->userable instanceof Admin;
	}

	/**
	 * @return int
	 * @throws \Exception
	 */
	public function unreadMessages()
	{
		/** @var integer $unread */
		static $unread = null;
		if (is_null($unread))
		{
			if ($this->isValidCompany())
				$unread = PrivateMessage
					::whereDirection('to_company')
					->whereCompanyId($this->userable->company->id)
					->whereRead(false)
					->count();

			elseif ($this->isEmployee())
				$unread = PrivateMessage
					::whereDirection('to_employee')
					->whereEmployeeId($this->userable->id)
					->whereRead(false)
					->count();
		}
		return $unread;
	}

	public function isEmployee()
	{
		return $this->userable instanceof Employee;
	}

	/**
	 * @return bool
	 */
	public function isInvited()
	{
		return UserInvite::whereEmail($this->email)->count() > 0;
	}

	public function sendEmailConfirmationNotification()
	{
		$this->notify(new \App\Notifications\ConfirmEmail($this));
	}

	public function sendPasswordResetNotification($token)
	{
		$this->notify(new \App\Notifications\ResetPassword($token));
	}
}
