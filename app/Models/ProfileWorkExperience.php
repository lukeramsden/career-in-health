<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileWorkExperience extends Model
{
    protected $fillable = ['job_title', 'company_name', 'start_date', 'end_date'];

    public function references()
    {
        return $this->hasMany('App\Reference', 'work_id');
    }

    public function profile() {
        return $this->belongsTo('App\Profile');
    }
}
