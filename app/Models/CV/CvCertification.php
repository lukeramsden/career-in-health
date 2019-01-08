<?php

namespace App\Cv;

use Illuminate\Support\Facades\Storage;

class CvCertification extends AbstractCvItemModel
{
  protected $table = 'cv_certifications';

  protected $fillable = [
	'title',
	'description',
	'start_date',
	'end_date',
  ];

  protected $dates = [
	'created_at',
	'updated_at',
	'start_date',
	'end_date',
  ];

  protected $appends = [
	'url', 'is_edited',
  ];

  protected static function boot()
  {
	parent::boot();

	static::deleting(function (CvCertification $cert) {
	  Storage::delete($cert->file);
	});
  }

  public function getUrlAttribute()
  {
	return Storage::url($this->file);
  }
}