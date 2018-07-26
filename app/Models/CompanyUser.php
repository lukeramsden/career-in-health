<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CompanyUser extends Model
{
    protected $appends = ['is_edited', 'full_name'];

    protected $fillable = [
        'first_name', 'last_name', 'job_title', 'company_id'
    ];

    public function getIsEditedAttribute()
    {
         return $this->created_at != $this->updated_at;
    }

    public function getFullNameAttribute()
    {
        return $this->fullName();
    }

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function fullName()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function picture()
    {
        return $this->avatar ? Storage::url($this->avatar) : null;
    }

    public function invites()
	{
		return $this->hasMany(CompanyUserInvite::class, 'invited_by_id');
	}
}
