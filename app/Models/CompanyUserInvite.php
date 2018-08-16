<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Webpatser\Uuid\Uuid;

class CompanyUserInvite extends Model
{
	use Notifiable;

	public    $incrementing = false;
	protected $table        = 'company_user_invites';
	protected $primaryKey   = 'email';
	protected $fillable     = [];
	protected $dates        = ['created_at', 'updated_at', 'last_reminded_at'];

	public static function boot()
	{
		parent::boot();
		self::creating(function ($model)
		{
			$model->accept_code = (string)Uuid::generate(4);
		});
	}

	public function company()
	{
		return $this->belongsTo(Company::class, 'company_id');
	}

	public function invitedBy()
	{
		return $this->belongsTo(CompanyUser::class, 'invited_by_id');
	}

	public function remind()
	{
		$this->notify(new \App\Notifications\CompanyUserInviteNotification($this));

		$this->last_reminded_at = now();
		$this->times_reminded   = $this->times_reminded++ ?? 1;
		$this->save();
	}
}
