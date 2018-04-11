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
}
