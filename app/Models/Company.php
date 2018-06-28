<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    protected $appends = ['is_edited'];

    public function getIsEditedAttribute()
    {
         return $this->attributes['is_edited'] = ($this->created_at != $this->updated_at) ? true : false;
    }

    protected $fillable = ['name', 'location_id', 'about'];

    protected static function boot() {
        parent::boot();

        static::deleting(function(Company $company) {
            $company->adverts()  ->delete();
            $company->addresses()->delete();
        });
    }

    public function adverts()
    {
        return $this->hasMany(\App\Advert::class);
    }

    public function addresses()
    {
        return $this->hasMany(\App\Address::class);
    }

    public function users()
    {
        return $this->hasMany(\App\CompanyUser::class);
    }

    public function location()
    {
        return $this->hasOne(Location::class, 'id', 'location_id');
    }

    public function applications()
    {
        return $this->hasManyThrough(\App\AdvertApplication::class, \App\Advert::class);
    }

    public function picture()
    {
        return $this->avatar ? Storage::url($this->avatar) : null;
    }
}
