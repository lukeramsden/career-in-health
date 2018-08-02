<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Advert extends Model
{
	protected $fillable = [
		'title',
		'body',
		'links_to',
	];

	protected static function boot()
	{
		parent::boot();

		static::created(function (Advertiser $advertiser)
		{
		});

		static::deleting(function (Advertiser $advertiser)
		{
			Storage::delete($advertiser->image_path);
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
