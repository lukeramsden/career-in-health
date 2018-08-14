<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Webpatser\Uuid\Uuid;

class AdvertiserInvite extends Model
{
	use Notifiable;

	public    $incrementing = false;
	protected $primaryKey   = 'email';
	protected $fillable     = [];
	protected $dates        = ['created_at', 'updated_at', 'last_reminded_at'];

	/**
	 * Get the route key for the model.
	 *
	 * @return string
	 */
	public function getRouteKeyName()
	{
		return 'accept_code';
	}

	public static function boot()
	{
		parent::boot();
		self::creating(function ($model)
		{
			$model->accept_code = (string)Uuid::generate(4);
		});
	}

	public function remind()
	{
		// TODO: advertiser invite notification
//		$this->notify(new \App\Notifications\CompanyInvite($this));

		$this->last_reminded_at = now();
		$this->times_reminded   = $this->times_reminded++ ?? 1;
		$this->save();
	}
}
