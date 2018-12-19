<?php

namespace App\Cv;

class CvPreferences extends AbstractCvItemModel
{
  static $salaryTypes = [
	'1' => 'Year',
	'2' => 'Month',
	'3' => 'Week',
	'4' => 'Day',
	'5' => 'Hour',
  ];

  protected $table = 'cv_preferences';

  protected $fillable = [
    'job_role',
	'setting',
	'type',
	'salary_number',
	'salary_type',
	'willing_to_relocate',
  ];

  public function cv()
  {
	return $this->belongsTo(Cv::class, 'preferences_id', 'id');
  }
}