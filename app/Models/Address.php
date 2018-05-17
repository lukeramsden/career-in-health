<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    
    protected $guarded = ['id', '_token'];
    protected $with = ['location'];

    protected static function boot() {
        parent::boot();

        static::deleting(function(Address $address) {
            $address->adverts()->delete();
        });
    }

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
        return $this->hasOne(\App\Location::class, 'id', 'location_id');
    }

    public function company()
    {
        return $this->belongsTo(\App\Company::class, 'company_id', 'id');
    }

    public function adverts()
    {
        return $this->hasMany(\App\Advert::class);
    }
}
