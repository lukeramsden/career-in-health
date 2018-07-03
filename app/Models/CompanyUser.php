<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CompanyUser extends Model
{
    protected $appends = ['is_edited', 'full_name'];

    public function getIsEditedAttribute()
    {
         return $this->attributes['is_edited'] = ($this->created_at != $this->updated_at) ? true : false;
    }

    public function getFullNameAttribute()
    {
        return $this->fullName();
    }

    protected $fillable = [
        'first_name', 'last_name'
    ];

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function fullName()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function picture()
    {
        return $this->avatar ? Storage::url($this->avatar) : null;
    }
}
