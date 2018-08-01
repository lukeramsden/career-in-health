<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
	protected $appends  = ['is_edited'];
	protected $fillable = ['name', 'location_id', 'about', 'phone', 'email'];

	protected static function boot()
	{
		parent::boot();

		static::deleting(function (Company $company)
		{
			$company->adverts()->delete();
			$company->addresses()->delete();
		});
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function adverts()
	{
		return $this->hasMany(\App\Advert::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function addresses()
	{
		return $this->hasMany(\App\Address::class);
	}

	public function getIsEditedAttribute()
	{
		return $this->created_at != $this->updated_at;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function users()
	{
		return $this->hasMany(\App\CompanyUser::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function location()
	{
		return $this->hasOne(Location::class, 'id', 'location_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
	 */
	public function applications()
	{
		return $this->hasManyThrough(\App\AdvertApplication::class, \App\Advert::class);
	}

	/**
	 * @return null|string
	 */
	public function picture()
	{
		return $this->avatar ? Storage::url($this->avatar) : null;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function invites()
	{
		return $this->hasMany(CompanyUserInvite::class);
	}

	/**
	 * @param      $permissionLevel
	 * @param bool $filterDeactivated
	 */
	public function getUsersWithPermissionLevel($permissionLevel, $filterDeactivated = true)
	{
		return CompanyUser
			::whereIn('id',
				DB::table('company_user_permissions')
				  ->where('company_id', $this->id)
				  ->where('permission_level', $permissionLevel)
				  ->pluck('company_user_id')
			)
			->get()
			->filter(function ($value, $key) use ($filterDeactivated)
			{
				return !$filterDeactivated || !$value->user->deactivated;
			});
	}

	/**
	 * @return CompanyUser|CompanyUser[]|\Illuminate\Database\Eloquent\Collection|Model|mixed|null
	 */
	public function owner()
	{
		return CompanyUser::find($this->owner_id);
	}

	/**
	 * @return \Illuminate\Support\Collection
	 */
	public function managers()
	{
		return $this->getUsersWithPermissionLevel('manager');
	}

	/**
	 * @return \Illuminate\Support\Collection
	 */
	public function standardUsers()
	{
		return $this->getUsersWithPermissionLevel('standard');
	}

	public function deactivatedUsers()
	{
		return CompanyUser
			::whereIn('id',
				DB::table('company_user_permissions')
				  ->where('company_id', $this->id)
				  ->pluck('company_user_id')
			)
			->get()
			->filter(function ($value, $key)
			{
				return $value->user->deactivated;
			});
	}

	/**
	 * @param User|CompanyUser $user
	 *
	 * @return bool
	 */
	public function isOwner($user)
	{
		return ($user instanceof User ?
				$user->userable->id
				: $user->id) === $this->owner_id;
	}

	/**
	 * @param string       $email
	 * @param integer|null $invited_by
	 *
	 * @return CompanyUserInvite
	 */
	public function invite(string $email, $invited_by = null)
	{
		$invite                = new CompanyUserInvite();
		$invite->email         = $email;
		$invite->company_id    = $this->id;
		$invite->invited_by_id = $invited_by ?? $this->owner_id;
		$invite->save();
		$invite->remind();

		return $invite;
	}

	/**
	 * @param CompanyUser $companyUser
	 *
	 * @return bool
	 */
	public function makeOwner(CompanyUser $companyUser)
	{
		$table = DB::table('company_user_permissions');

		$table
			->where('company_user_id', $this->owner()->id)
			->update([
				'permission_level' => 'manager',
			]);

		$table
			->where('company_user_id', $companyUser->id)
			->update([
				'permission_level' => 'owner',
			]);

		$this->owner_id = $companyUser->id;
		$this->save();

		return true;
	}
}
