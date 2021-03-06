<?php

namespace App;

use App\Advertising\Advertiser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
  use Notifiable;
  use \Calebporzio\Onboard\GetsOnboarded;
  use \Laravel\Cashier\Billable;

  protected $fillable = [
	'email',
  ];

  protected $hidden = [
	'password', 'remember_token',
  ];

  protected $appends = [
	'user_type_name',
  ];

  protected static function boot()
  {
	parent::boot();

	static::created(function (User $user) {
	  DB
		::table('notification_preferences')
		->insert(['id' => $user->id]);
	});

	static::deleting(function (User $user) {
	  $user
		->userable()
		->delete();
	});
  }

  /**
   * @return null|string
   */
  public function getUserTypeNameAttribute()
  {
	if ($this->userable instanceof \App\Employee)
	  return 'employee';


	if ($this->userable instanceof \App\CompanyUser)
	  return 'company';


	if ($this->userable instanceof \App\Admin)
	  return 'admin';


	if ($this->userable instanceof \App\Advertising\Advertiser)
	  return 'advertiser';


	return null;
  }

  /**
   * @return \Illuminate\Database\Query\Builder
   */
  public function notificationPreferences()
  {
	return DB
	  ::table('notification_preferences')
	  ->where('id', $this->id);
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

  public function isEmployee()
  {
	return $this->userable instanceof Employee;
  }

  public function isAdmin()
  {
	return $this->userable instanceof Admin;
  }

  public function isAdvertiser()
  {
	return $this->userable instanceof Advertiser;
  }

  /**
   * @return bool
   * @throws \Exception
   */
  public function hasCreatedJobListing()
  {
	static $b = null;
	if (is_null($b))
	  // has_created_first_job_listing is here because we don't want to return to onboarding
	  // if the company deletes all the jobListings they have created
	  $b = $this->isValidCompany() && $this->userable->company->has_created_first_job_listing;
	return $b;
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

  /**
   * @return bool
   */
  public function isInvited()
  {
	return CompanyUserInvite::whereEmail($this->email)->count() > 0;
  }

  public function sendEmailConfirmationNotification()
  {
	$this->notify(new \App\Notifications\ConfirmEmail($this));
  }

  public function sendPasswordResetNotification($token)
  {
	$this->notify(new \App\Notifications\ResetPassword($token));
  }

  public function activate()
  {
	if ($this->deactivated)
	{
	  $this->deactivated = false;
	  $this->save();
	}
  }

  public function deactivate()
  {
	if (!$this->deactivated)
	{
	  $this->deactivated = true;
	  $this->save();
	}
  }
}
