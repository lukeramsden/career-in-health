<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'avatar'
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
}
