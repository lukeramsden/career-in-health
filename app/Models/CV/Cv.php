<?php

namespace App\Cv;

use App\Model\CustomModel as Model;

class Cv extends Model
{
  protected $fillable = [];

  protected static function boot()
  {
	parent::boot();

	static::created(function (Cv $cv) {
	  $preferences = new CvPreferences();
	  $preferences->save();
	  $cv->preferences_id = $preferences->id;
	  $cv->save();
	});

	static::deleting(function (Cv $cv) {
	  $cv->education()->delete();
	  $cv->workExperience()->delete();
	  $cv->certifications()->delete();
	  $cv->preferences()->delete();
	});

	static::saved(function (Cv $cv) {

	});
  }

  public function education()
  {
	return $this->hasMany(CvEducation::class);
  }

  public function workExperience()
  {
	return $this->hasMany(CvWorkExperience::class);
  }

  public function certifications()
  {
	return $this->hasMany(CvCertification::class);
  }

  public function preferences()
  {
	return $this->hasOne(CvPreferences::class, 'id', 'preferences_id');
  }

  public function employee()
  {
	return $this->belongsTo(\App\Employee::class);
  }
}
