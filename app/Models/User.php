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

    protected $with = ['profile'];

    protected static function boot() {
        parent::boot();

        static::deleting(function(User $user) {
        });
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPassword($token));
    }

    public function sendEmailConfirmationNotification()
    {
        $this->notify(new \App\Notifications\ConfirmEmail($this));
    }

    public function cv()
    {
        return $this->hasOne(Cv\Cv::class);
    }
}
