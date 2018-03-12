<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    
    public function adverts()
    {
        return $this->hasMany('App\Models\Advert');
    }

}
