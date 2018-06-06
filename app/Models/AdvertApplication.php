<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class AdvertApplication extends Model
{
    static $statuses = [
        '0' => 'Applied',
        '1' => 'Shortlisted',
        '2' => 'Offered',
        '3' => 'Rejected',
    ];

    protected $fillable = ['custom_cover_letter'];
    protected $dates = ['created_at', 'updated_at', 'last_edited'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
    
    public function advert()
    {
        return $this->belongsTo(\App\Advert::class);
    }

    /**
     * @param \App\User|null $user
     * @param Advert|null $advert
     * @return AdvertApplication|null
     */
    static public function getApplication(User $user = null, Advert $advert = null)
    {
        if($user == null || $advert == null) return null;

        return static::where([
            ['user_id', $user->id],
            ['advert_id', $advert->id]
        ])->first();
    }

    /**
     * @param \App\User|null $user
     * @param Advert|null $advert
     * @return bool
     */
    static public function hasApplied(User $user = null, Advert $advert = null)
    {
        if($user == null || $advert == null) return false;

        return static::where([
            ['user_id', $user->id],
            ['advert_id', $advert->id]
        ])->count() > 0 ? true : false;
    }

}
