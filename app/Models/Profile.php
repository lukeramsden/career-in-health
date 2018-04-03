<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    protected $fillable = ["first_name", "last_name", "headline", "location", "description"];

    public function fullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function work()
    {
        return $this->hasMany('App\ProfileWorkExperience');
    }

    public function references()
    {
        return $this->hasMany('App\Reference');
    }

    public function certifications()
    {
        return $this->hasMany('App\Certification');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function picture()
    {
        ///TODO: add our own default profile picture (potentially do it front-end)
        return $this->avatar_path ? Storage::url($this->avatar_path) : null;
    }

    public function jobTypes()
    {
        return $this->belongsToMany('App\JobType');
    }
}
