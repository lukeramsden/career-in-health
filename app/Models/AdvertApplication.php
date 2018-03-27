<?php

namespace App\Models;

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
}
