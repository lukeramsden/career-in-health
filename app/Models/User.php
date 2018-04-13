<?php

namespace App;

use Laravel\Cashier\Billable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use Billable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $with = ['profile'];

    public function isCompany()
    {
        return $this->company_id !== null ? true : false; // not sure why you don't just return the expression james
    }

    public function company()
    {
        return $this->belongsTo(\App\Company::class);
    }

    public function stripePlan()
    {
        return $this->hasMany(\App\Subscribe::class);
    }

    public function applications()
    {
        return $this->hasMany(\App\AdvertApplication::class);
    }

    public function activeStripePlan()
    {
        return $this->getSubscriptionUser()
            ->stripePlan()
            ->orderBy('created_at', 'DESC')
            ->first();
    }
    
    public function getSubscriptionUser()
    {
        $user = $this->company->users()
            ->whereNotNull('stripe_id')
            ->first();

        if ($user == null) {
            $user = $this->company->users()
                ->first();
        }

        return $user;
    }

    public function profile()
    {
        return $this->hasOne(\App\Profile::class);
    }

    public function cv()
    {
        return $this->hasOne(\App\Cv\Cv::class);
    }
}
