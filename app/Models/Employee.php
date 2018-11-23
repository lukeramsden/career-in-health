<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Employee extends Model
{
  protected $appends = ['is_edited', 'full_name'];
  protected $fillable = [
	'first_name', 'last_name', 'about', 'location_id',
  ];

  protected static function boot()
  {
	parent::boot();

	static::created(function (Employee $employee) {
	  $cv = new Cv\Cv();
	  $employee->cv()->save($cv);
	  $employee->save();
	});

	static::deleting(function (Employee $employee) {
	  $employee->cv()->delete();
	});
  }

  public function cv()
  {
	return $this->hasOne(Cv\Cv::class);
  }

  public function getIsEditedAttribute()
  {
	return $this->created_at != $this->updated_at;
  }

  public function getFullNameAttribute()
  {
	return $this->fullName();
  }

  public function fullName()
  {
	return trim("{$this->first_name} {$this->last_name}");
  }

  public function user()
  {
	return $this->morphOne(User::class, 'userable');
  }

  public function applications()
  {
	return $this->hasMany(JobListingApplication::class);
  }

  public function location()
  {
	return $this->hasOne(Location::class, 'id', 'location_id');
  }

  public function picture()
  {
	return $this->avatar ? Storage::url($this->avatar) : null;
  }

  public function saveJobListing(JobListing $jobListing)
  {
	if (SavedJobListing::where([
		'employee_id'    => $this->id,
		'job_listing_id' => $jobListing->id,
	  ])->count() == 0)
	{

	  $savedJobListing                 = new SavedJobListing();
	  $savedJobListing->employee_id    = $this->id;
	  $savedJobListing->job_listing_id = $jobListing->id;
	  $savedJobListing->save();
	}
  }

  public function unsaveJobListing(JobListing $jobListing)
  {
	try
	{
	  SavedJobListing::where([
		'employee_id'    => $this->id,
		'job_listing_id' => $jobListing->id,
	  ])->delete();
	} catch (\Exception $e)
	{
	}
  }

  public function isJobListingSaved(JobListing $jobListing)
  {
	return SavedJobListing::where([
		'employee_id'    => $this->id,
		'job_listing_id' => $jobListing->id,
	  ])->count() > 0;
  }
}
