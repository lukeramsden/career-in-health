<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class JobListingApplication extends Model
{
    static $statuses = [
        0 => 'Applied',
        1 => 'Shortlisted',
        2 => 'Offered',
        3 => 'Rejected',
    ];

    protected $fillable = ['custom_cover_letter'];
    protected $dates    = ['created_at', 'updated_at', 'last_edited'];
	protected $appends  = ['status_name', 'permalink'];

	public function getStatusNameAttribute()
	{
		return static::$statuses[$this->status ?? 0	];
	}

	public function getPermalinkAttribute()
	{
		return '';
	}

    public function employee()
    {
        return $this->belongsTo(\App\Employee::class);
    }
    
    public function job_listing()
    {
        return $this->belongsTo(\App\JobListing::class);
    }

	public function jobListing()
 {
     return $this->belongsTo(\App\JobListing::class);
 }

    /**
     * @param \App\Employee|null $employee
     * @param JobListing|null $jobListing
     * @return JobListingApplication|null
     */
    static public function getApplication(Employee $employee = null, JobListing $jobListing = null)
    {
        if($employee == null || $jobListing == null) return null;

        return static::where([
            ['employee_id', $employee->id],
            ['job_listing_id', $jobListing->id]
        ])->first();
    }

    /**
     * @param \App\Employee|null $employee
     * @param JobListing|null $jobListing
     * @return bool
     */
    static public function hasApplied(Employee $employee = null, JobListing $jobListing = null)
    {
        if($employee == null || $jobListing == null) return false;

        return static::where([
            ['employee_id', $employee->id],
            ['job_listing_id', $jobListing->id]
        ])->count() > 0 ? true : false;
    }

}
