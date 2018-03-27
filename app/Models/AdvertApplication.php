<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class AdvertApplication extends Model
{
    protected $fillable = ['custom_cover_letter'];

    public function user()
    {
        $this->belongsTo('App\Models\User');
    }
    
    public function advert()
    {
        $this->belongsTo('App\Models\Advert');
    }

    static public function alreadyApplied(User $user, Advert $advert)
    {
            return AdvertApplication::where([
                ['user_id', $user->id],
                ['advert_id', $advert->id]
            ])->count() > 0 ? true : false;
    }

}
