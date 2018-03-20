<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $fillable = ['person_name', 'person_company', 'person_relation', 'person_contact', 'work_id'];

    public function work()
    {
        return $this->belongsTo('App\Models\ProfileWorkExperience');
    }

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile');
    }
}
