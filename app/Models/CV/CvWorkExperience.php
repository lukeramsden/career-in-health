<?php

namespace App\Cv;

class CvWorkExperience extends AbstractCvItemModel
{
  protected $table = 'cv_work_experience';

  protected $fillable = [
	'job_title',
	'company_name',
	'description',
	'location',
	'start_date',
	'end_date',
  ];

  protected $dates = [
	'created_at',
	'updated_at',
	'start_date',
	'end_date',
  ];
}