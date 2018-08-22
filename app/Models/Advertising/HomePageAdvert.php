<?php

namespace App\Advertising;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HomePageAdvert extends Model
{
	protected $fillable = [
		'image_path',
		'links_to',
	];

	protected static function boot()
	{
		parent::boot();

		static::created(function (HomePageAdvert $advert)
		{
		});

		static::deleting(function (HomePageAdvert $advert)
		{
			Storage::delete($advert->image_path);
		});
	}

	public function advert()
	{
		return $this->morphOne(Advert::class, 'advertable');
	}
}
