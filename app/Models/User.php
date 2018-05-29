<?php

namespace App;

use Laravel\Cashier\Billable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

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

    protected static function boot() {
        parent::boot();

        static::deleting(function(User $user) {
             $user->profile()->delete();

             if($user->isCompany())
                 $user->company()->delete();
             else $user->cv()->delete();

             $user->applications()->delete();
        });
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function isCompany()
    {
        return $this->company_id !== null ? true : false; // not sure why you don't just return the expression james
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

    public function company()
    {
        return $this->belongsTo(\App\Company::class);
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
