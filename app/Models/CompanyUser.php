<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }
}
