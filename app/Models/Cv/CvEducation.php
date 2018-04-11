<?php

namespace App\Cv;

class CvEducation extends AbstractCvItemModel
{
    protected $fillable = ['degree', 'school_name', 'field_of_study', 'location', 'start_date', 'end_date'];
}