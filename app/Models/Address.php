<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class Address extends Model implements HasMedia
{
	use HasMediaTrait;

    protected $fillable = [
    	'name',
		'address_line_1',
		'address_line_2',
		'address_line_3',
		'county',
		'postcode',
		'company_id',
		'location_id',
	];

    protected $with = ['location'];

    protected static function boot() {
        parent::boot();

        static::deleting(function(Address $address) {
            $address->jobListings()->delete();
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

    public function jobListings()
    {
        return $this->hasMany(\App\JobListing::class);
    }
}
