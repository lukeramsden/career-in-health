<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    
    protected $guarded = ['id', '_token'];
    protected $with = ['location'];

    public function location()
    {
        return $this->hasOne('App\Models\Location', 'id', 'town');
    }

    public function getAllLocations()
    {
        $locations = Location::select(['id', 'name', 'county'])
            ->whereIn('type', ['City', 'Town'])
            ->get();

        $locs = [];

        foreach ($locations as $loc) {
            $locs[] = (object) [
                'id' => $loc->id,
                'name' => str_replace("'", '', $loc->name) .' ('. $loc->county .')',
            ];
        }  

        $locs = collect($locs)->unique('name')->all();

        return $locs;
    }

}
