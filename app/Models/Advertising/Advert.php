<?php

namespace App\Advertising;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Advert extends Model
{
	protected $fillable = [];

	protected static function boot()
	{
		parent::boot();

		static::created(function (Advert $advert)
		{
		});

		static::deleting(function (Advert $advert)
		{
			$advert->advertable()->delete();
		});
	}

	public function advertable()
	{
		return $this->morphTo();
	}

	public function advertiser()
	{
		return $this->belongsTo(Advertiser::class);
	}
}
