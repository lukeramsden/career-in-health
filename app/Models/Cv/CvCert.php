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

    protected static function boot() {
        parent::boot();

        static::deleting(function(CvCert $cert) {
            Storage::delete($cert->file);
        });
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->file);
    }
}