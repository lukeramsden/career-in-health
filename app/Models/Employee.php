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

        static::deleting(function(Cv\Cv $cv) {
            $cv->delete();
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
