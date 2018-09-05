<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SavedJobListing extends Model
{
    protected $fillable = [];
	protected $table = 'employee_saved_job_listings';

 	public function jobListing()
	{
		return $this->belongsTo(JobListing::class);
	}

	public function employee()
	{
		return $this->belongsTo(Employee::class);
	}
}
