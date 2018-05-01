<?php

namespace App\Cv;

class CvCert extends AbstractCvItemModel
{
    protected $table = 'cv_certs';

    protected $fillable = ['title', 'description', 'start_date', 'end_date'];

    protected $hidden = ['cv_id', 'file'];

    protected $dates = [
        'created_at',
        'updated_at',
        'start_date',
        'end_date',
    ];
}