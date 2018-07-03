<?php

namespace App\Cv;

use Illuminate\Database\Eloquent\Model;

class Cv extends Model
{
    protected $fillable = [];
    protected $appends = ['is_edited'];

    public function getIsEditedAttribute()
    {
         return $this->attributes['is_edited'] = ($this->created_at != $this->updated_at) ? true : false;
    }

    protected static function boot() {
        parent::boot();

        static::created(function(Cv $cv) {
           $preferences = new CvPreferences();
           $preferences->save();
           $cv->preferences_id = $preferences->id;
           $cv->save();
        });

        static::deleting(function(Cv $cv) {
            $cv->education()     ->delete();
            $cv->workExperience()->delete();
            $cv->certifications()->delete();
            $cv->preferences()   ->delete();
        });

        static::saved(function(Cv $cv) {

        });
    }

    public function employee()
    {
        return $this->belongsTo(\App\Employee::class);
    }

    public function education()
    {
        return $this->hasMany(CvEducation::class);
    }

    public function workExperience()
    {
        return $this->hasMany(CvWorkExperience::class);
    }

    public function certifications()
    {
        return $this->hasMany(CvCert::class);
    }

    public function preferences()
    {
        return $this->hasOne(CvPreferences::class, 'id', 'preferences_id');
    }
}
