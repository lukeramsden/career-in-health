<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    
    protected $guarded = ['id', '_token'];
    protected $with = ['location'];

    public function location()
    {
        return $this->hasOne(\App\Location::class, 'id', 'town');
    }

}
