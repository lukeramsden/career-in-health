<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'email',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function boot() {
        parent::boot();

        static::deleting(function(User $user) {
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
     */
    public function isValidCompany()
    {
        return $this->isCompany() && $this->userable->company()->exists();
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
