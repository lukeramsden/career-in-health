<?php

namespace App;

use Akaunting\Money\Money;
use Illuminate\Database\Eloquent\Model;

class JobListing extends Model
{
  use \Watson\Rememberable\Rememberable;

  static    $settings = [
	'1' => 'Care Home / Nursing Home',
	'2' => 'Housing with Care',
	'3' => 'Hospital',
	'4' => 'Adult Day Care Centre',
	'5' => 'Charities / Association / Org',
	'6' => 'Care Provider HQ / Office',
	'7' => 'Other',
  ];
  static    $types    = [
	'1' => 'Full Time',
	'2' => 'Part Time',
	'3' => 'Full Time or Part Time',
	'4' => 'Temporary / Cover',
	'5' => 'Contract',
	'6' => 'Voluntary',
  ];
  protected $with     = ['address'];
  protected $guarded  = ['_token', 'id', 'savingForLater'];
  protected $dates    = ['created_at', 'updated_at', 'started_at', 'end_at', 'last_edited'];
  protected $appends  = [
	'type_name',
	'setting_name',
	'min_salary_formatted',
	'max_salary_formatted',
	'status_name',
  ];

  protected static function boot()
  {
	parent::boot();

	static::deleting(function (JobListing $jobListing) {
	  $jobListing->applications()->delete();
	  $jobListing->messages()->delete();
	});
  }

  public function applications()
  {
	return $this->hasMany(\App\JobListingApplication::class);
  }

  public function messages()
  {
	return $this->hasMany(PrivateMessage::class, 'job_listing_id');
  }

  public function getSettingNameAttribute()
  {
	if (!$this->setting) return null;
	return self::$settings[$this->setting];
  }

  public function getTypeNameAttribute()
  {
	if (!$this->type) return null;
	return self::$types[$this->type];
  }

  public function getMinSalaryFormattedAttribute()
  {
	return Money::GBP($this->min_salary * 100)->format();
  }

  public function getMaxSalaryFormattedAttribute()
  {
	return Money::GBP($this->max_salary * 100)->format();
  }

  public function getStatusNameAttribute()
  {
	return $this->isOpen()
	  ? $this->isPublished()
		? 'Published'
		: 'Draft'
	  : 'Closed';
  }

  public function isOpen()
  {
	return is_null($this->closed_at);
  }

  public function isPublished()
  {
	return $this->published;
  }

  public function jobRole()
  {
	return $this->hasOne(\App\JobRole::class, 'id', 'job_role');
  }

  public function company()
  {
	return $this->belongsTo(\App\Company::class, 'company_id', 'id');
  }

  public function address()
  {
	return $this->hasOne(\App\Address::class, 'id', 'address_id');
  }

  public function linkEdit()
  {
	return route('job-listing.edit', [
	  $this->id,
	]);
  }

  public function getDistanceToAddress(Address $address)
  {
	return $this->getDistanceToLocation($address->location);
  }

  public function getDistanceToLocation(Location $location)
  {
	$lat1 = $this->address->location->latitude;
	$lon1 = $this->address->location->longitude;
	$lat2 = $location->latitude;
	$lon2 = $location->longitude;

	$theta = $lon1 - $lon2;
	$dist  = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	$dist  = acos($dist);
	$dist  = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;

	return $miles;
  }

  public function isDraft()
  {
	return !$this->published;
  }

  public function reopen()
  {
	$this->closed_at    = null;
	$this->close_reason = null;
	$this->save();
  }

  public function close($reason = null)
  {
	$this->closed_at    = now();
	$this->close_reason = $reason;
	$this->save();
  }
}
