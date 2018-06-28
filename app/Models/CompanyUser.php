<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CompanyUser extends Model
{
    protected $appends = ['is_edited'];

    public function getIsEditedAttribute()
    {
         return $this->attributes['is_edited'] = ($this->created_at != $this->updated_at) ? true : false;
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

    public function picture()
    {
        return $this->avatar ? Storage::url($this->avatar) : null;
    }
}
