<?php

namespace App\Cv;

class CvCert extends AbstractCvItemModel
{
    protected $table = 'cv_certs';

    protected $fillable = ['title', 'description', 'start_date', 'end_date', 'file'];

    protected $hidden = ['cv_id'];

    protected $dates = [
        'created_at',
        'updated_at',
        'start_date',
        'end_date',
    ];
}