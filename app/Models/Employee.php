<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Employee extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'about', 'location_id'
    ];

    protected static function boot() {
        parent::boot();

        static::created(function(Employee $employee) {
            $cv = new Cv\Cv();
            $employee->cv()->save($cv);
            $employee->save();
        });

        static::deleting(function(Employee $employee) {
            $employee->cv()->delete();
        });
    }

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function cv()
    {
        return $this->hasOne(Cv\Cv::class);
    }

    public function applications()
    {
        return $this->hasMany(AdvertApplication::class);
    }

    public function location()
    {
        return $this->hasOne(Location::class, 'id', 'location_id');
    }

    public function fullName()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function picture()
    {
        return $this->avatar ? Storage::url($this->avatar) : null;
    }

}
