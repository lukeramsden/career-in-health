<?php

namespace App\Cv;

use Illuminate\Support\Facades\Storage;

class CvCert extends AbstractCvItemModel
{
    protected $table = 'cv_certs';

    protected $fillable = ['title', 'description', 'start_date', 'end_date'];

    protected $hidden = ['cv_id'];

    protected $dates = [
        'created_at',
        'updated_at',
        'start_date',
        'end_date',
    ];

    protected $appends = [
        'url',
    ];

    public function getUrlAttribute()
    {
        return Storage::url($this->file);
    }
}