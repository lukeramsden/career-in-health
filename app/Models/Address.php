<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    
    protected $guarded = ['id', '_token'];
    protected $with = ['location'];

    public function getPostcodeAttribute($value)
    {
        return strtoupper($value);
    }

    public function setPostcodeAttribute($value)
    {
        $this->attributes['postcode'] = strtoupper($value);
    }

    public function location()
    {
        return $this->hasOne(\App\Location::class, 'id', 'town');
    }

    public function company()
    {
        return $this->belongsTo(\App\Company::class, 'company_id', 'id');
    }
}
