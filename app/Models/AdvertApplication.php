<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class AdvertApplication extends Model
{
    static $statuses = [
        '1' => 'Hire',
        '2' => 'Don\'t Hire',
        '3' => 'Interview',
        '4' => 'Interested',
    ];

    protected $fillable = ['custom_cover_letter'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function advert()
    {
        return $this->belongsTo('App\Models\Advert');
    }

    static public function alreadyApplied(User $user = null, Advert $advert = null)
    {
        if($user == null || $advert == null) return false;

        return AdvertApplication::where([
            ['user_id', $user->id],
            ['advert_id', $advert->id]
        ])->count() > 0 ? true : false;
    }

}
