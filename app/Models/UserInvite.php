<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInvite extends Model
{
    protected $table = 'user_invites';
    protected $fillable = [];

    public function company()
	{
		return $this->belongsTo(Company::class, 'company_id');
	}

	public function invitedBy()
	{
		return $this->belongsTo(CompanyUser::class, 'invited_by_id');
	}
}
