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
}
