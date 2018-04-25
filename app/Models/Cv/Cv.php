<?php

namespace App\Cv;

use Illuminate\Database\Eloquent\Model;

class Cv extends Model
{
    protected $fillable = [];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function education()
    {
        return $this->hasMany(CvEducation::class);
    }

    public function workExperience()
    {
        return $this->hasMany(CvWorkExperience::class);
    }

    public function preferences()
    {
        return $this->hasOne(CvPreferences::class, 'id', 'preferences_id');
    }
}
