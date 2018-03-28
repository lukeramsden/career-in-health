<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    
    public function adverts()
    {
        return $this->hasMany('App\Models\Advert');
    }

    public function addresses()
    {
        return $this->hasMany('App\Models\Address');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function applications()
    {
        return $this->hasManyThrough('App\Models\AdvertApplication', 'App\Models\Advert');
    }

}
