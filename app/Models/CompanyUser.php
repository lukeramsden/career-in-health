<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
