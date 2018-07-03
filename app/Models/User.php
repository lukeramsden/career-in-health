<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use \Calebporzio\Onboard\GetsOnboarded;

    protected $fillable = [
        'email',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (User $user)
        {
            $user->userable()->delete();
        });
    }

    public function userable()
    {
        return $this->morphTo();
    }

    public function isEmployee()
    {
        return $this->userable instanceof Employee;
    }

    public function isCompany()
    {
        return $this->userable instanceof CompanyUser;
    }

    /**
     * Checks if is company user AND if company is valid
     *
     * @return bool
     * @throws \Exception
     */
    public function isValidCompany()
    {
        static $b = null;
        if(is_null($b))
            $b = $this->isCompany() && $this->userable->company()->exists();
        return $b;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function hasCreatedAddress()
    {
        static $b = null;
        if(is_null($b))
            $b = $this->isValidCompany() && $this->userable->company->addresses()->count() > 0;
        return $b;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function hasCreatedAdvert()
    {
        static $b = null;
        if(is_null($b))
            // has_created_first_advert is here because we don't want to return to onboarding
            // if the company deletes all the adverts they have created
            $b = $this->isValidCompany() && $this->userable->company->has_created_first_advert;
        return $b;
    }

    public function isAdmin()
    {
        return $this->userable instanceof Admin;
    }

    public function sendEmailConfirmationNotification()
    {
        $this->notify(new \App\Notifications\ConfirmEmail($this));
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPassword($token));
    }
}
