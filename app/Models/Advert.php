<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Advert extends Model
{
	protected $fillable = [
		// core
		'title',
		'body',
		'image_path',
		'links_to',
		'location_id',
		// demographics
		'dem_location_id',
		'dem_location_any',
		'dem_job_role_id',
		'dem_job_role_any',
		'dem_will_relocate',
	];

	protected static function boot()
	{
		parent::boot();

		static::created(function (Advert $advert)
		{
		});

		static::deleting(function (Advert $advert)
		{
			Storage::delete($advert->image_path);
		});
	}

	public function advertiser()
	{
		return $this->belongsTo(Advertiser::class);
	}

	public function image()
	{
		return $this->image_path ? Storage::url($this->image_path) : null;
	}
}
