<?php

namespace App\Advertising;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Advert extends Model
{
	const TYPE_HOMEPAGE = 1;

	protected $fillable = [];
	protected $with = ['advertable'];

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

	/**
	 * @return int
	 * @throws \Exception
	 */
	public function type()
	{
		if($this->advertable instanceof HomePageAdvert)
			return static::TYPE_HOMEPAGE;

		throw new \Exception('advertable not instanceof any known type');
	}
}
