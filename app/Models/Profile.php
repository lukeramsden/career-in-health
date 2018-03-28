<?php

namespace App\Models;

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
        return $this->hasMany('App\Models\ProfileWorkExperience');
    }

    public function references()
    {
        return $this->hasMany('App\Models\Reference');
    }

    public function certifications()
    {
        return $this->hasMany('App\Models\Certification');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function picture()
    {
        ///TODO: add our own default profile picture (potentially do it front-end)
        return $this->avatar_path ? Storage::url($this->avatar_path) : 'http://upload.wikimedia.org/wikipedia/commons/1/1e/Default-avatar.jpg';
    }

    public function jobTypes()
    {
        return $this->belongsToMany('App\Models\JobType');
    }
}
