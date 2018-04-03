<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    protected $fillable = ['name', 'headline', 'location', 'description', 'phone', 'contact_email'];
    
    public function adverts()
    {
        return $this->hasMany('App\Advert');
    }

    public function addresses()
    {
        return $this->hasMany('App\Address');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function applications()
    {
        return $this->hasManyThrough('App\AdvertApplication', 'App\Advert');
    }

    public function picture()
    {
        return $this->avatar_path ? Storage::url($this->avatar_path) : null;
    }

}
