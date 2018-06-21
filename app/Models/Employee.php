<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'avatar'
    ];

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }
}
