<?php

namespace App\Cv;

class CvPreferences extends AbstractCvItemModel
{
    protected $fillable = ['job_type_id', 'setting', 'type', 'salary', 'willing_to_relocate'];
}