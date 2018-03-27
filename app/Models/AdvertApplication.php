<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdvertApplication extends Model
{
    protected $fillable = [];

    public function advert()
    {
        $this->belongsTo('App\Models\Advert');
    }
}
