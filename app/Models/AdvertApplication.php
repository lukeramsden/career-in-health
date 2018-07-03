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

    public function employee()
    {
        return $this->belongsTo(\App\Employee::class);
    }
    
    public function advert()
    {
        return $this->belongsTo(\App\Advert::class);
    }

    /**
     * @param \App\Employee|null $employee
     * @param Advert|null $advert
     * @return AdvertApplication|null
     */
    static public function getApplication(Employee $employee = null, Advert $advert = null)
    {
        if($employee == null || $advert == null) return null;

        return static::where([
            ['employee_id', $employee->id],
            ['advert_id', $advert->id]
        ])->first();
    }

    /**
     * @param \App\Employee|null $employee
     * @param Advert|null $advert
     * @return bool
     */
    static public function hasApplied(Employee $employee = null, Advert $advert = null)
    {
        if($employee == null || $advert == null) return false;

        return static::where([
            ['employee_id', $employee->id],
            ['advert_id', $advert->id]
        ])->count() > 0 ? true : false;
    }

}
