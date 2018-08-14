<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertiser extends Model
{
	protected $fillable = ['name'];

	protected static function boot()
	{
		parent::boot();

		static::created(function (Advertiser $advertiser)
		{
		});

		static::deleting(function (Advertiser $advertiser)
		{
			$advertiser->adverts()->delete();
		});
	}

	public function user()
	{
		return $this->morphOne(User::class, 'userable');
	}

	public function adverts()
	{
		return $this->hasMany(Advert::class);
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
