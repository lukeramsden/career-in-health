<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function fullName() 
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function isCompany()
    {
        return $this->company_id !== null ? true : false;
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }
}
