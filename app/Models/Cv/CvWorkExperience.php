<?php

namespace App\Cv;

class CvWorkExperience extends AbstractCvItemModel
{
    protected $fillable = ['job_title', 'company_name', 'description', 'location', 'start_date', 'end_date'];
}