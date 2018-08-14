<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CompanyUser extends Model
{
	protected $appends = ['is_edited', 'full_name', 'permission_level'];

	protected $fillable = [
		'first_name', 'last_name', 'job_title', 'company_id',
	];

	public function getIsEditedAttribute()
	{
		return $this->created_at != $this->updated_at;
	}

	public function getFullNameAttribute()
	{
		return $this->fullName();
	}

	public function fullName()
	{
		return trim("{$this->first_name} {$this->last_name}");
	}

	public function getPermissionLevelAttribute()
	{
		return $this->permissionLevel();
	}

	public function permissionLevel()
	{
		return DB
			::table('company_user_permissions')
			->where('company_user_id', $this->id)
			->first()
			->permission_level;
	}

	public function user()
	{
		return $this->morphOne(User::class, 'userable');
	}

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function picture()
	{
		return $this->avatar ? Storage::url($this->avatar) : null;
	}

	public function invites()
	{
		return $this->hasMany(CompanyUserInvite::class, 'invited_by_id');
	}

	public function hasPermsOver(CompanyUser $user)
	{
		if ($this->ownsCompany())
			return true;

		if ($this->permission_level === 'manager'
			&& $user->permission_level === 'standard')
			return true;

		return false;
	}

	public function ownsCompany()
	{
		return $this->company->isOwner($this);
	}

	public function hasUserManagePerms()
	{
		if ($this->ownsCompany())
			return true;

		if ($this->permission_level === 'manager')
			return true;

		return false;
	}

	public function isActive()
	{
		return !$this->user->deactivated;
	}

	public function activate()
	{
		$this->user->activate();
	}

	public function deactivate()
	{
		$this->user->deactivate();
	}
}
