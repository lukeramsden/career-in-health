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

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPassword($token));
    }

    public function sendEmailConfirmationNotification()
    {
        $this->notify(new \App\Notifications\ConfirmEmail($this));
    }

    public function isCompany()
    {
        return $this->company_id !== null;
    }

    public function stripePlan()
    {
        return $this->hasMany(Subscribe::class);
    }

    public function applications()
    {
        return $this->hasMany(AdvertApplication::class);
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
        $user =$this
            ->company
            ->users()
            ->whereNotNull('stripe_id')
            ->first();

        if ($user == null)
            $user = $this
                ->company
                ->users()
                ->first();

        return $user;
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function cv()
    {
        return $this->hasOne(Cv\Cv::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(PrivateMessage::class, 'from_user_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(PrivateMessage::class, 'to_user_id');
    }

    public function unreadMessages()
    {
        return $this->receivedMessages()->where('read', false);
    }
}
