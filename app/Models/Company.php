<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    protected $fillable = ['name', 'headline', 'location', 'description'];
    
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

    public function picture()
    {
        ///TODO: add our own default profile picture (potentially do it front-end)
        return $this->avatar_path ? Storage::url($this->avatar_path) : 'http://upload.wikimedia.org/wikipedia/commons/1/1e/Default-avatar.jpg';
    }

}
