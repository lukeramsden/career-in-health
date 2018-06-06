<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    protected $fillable = ["first_name", "last_name", "phone", "headline", "location", "description"];

    protected static function boot() {
        parent::boot();

        static::deleting(function(Profile $profile) {
            Storage::delete($profile->avatar_path);
        });
    }

    public function fullName()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function picture()
    {
        return $this->avatar_path ? Storage::url($this->avatar_path) : null;
    }

    public function jobRoles()
    {
        return $this->belongsToMany(\App\JobRole::class);
    }
}
