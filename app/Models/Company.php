<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    protected $fillable = ['name', 'headline', 'location', 'description', 'phone', 'contact_email'];

    protected static function boot() {
        parent::boot();

        static::deleting(function(Company $company) {
            $company->adverts()->delete();
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
        return $this->hasMany(\App\User::class);
    }

    public function applications()
    {
        return $this->hasManyThrough(\App\AdvertApplication::class, \App\Advert::class);
    }

    public function picture()
    {
        return $this->avatar_path ? Storage::url($this->avatar_path) : null;
    }

    public function messages()
    {
        return $this->hasManyThrough(PrivateMessage::class, Advert::class);
    }

    public function unreadMessages()
    {
        return $this->messages()->where('read', false);
    }
}
