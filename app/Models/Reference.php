<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $fillable = ['person_name', 'person_company', 'person_relation', 'person_contact', 'work_id'];

    public function work()
    {
        return $this->belongsTo('App\ProfileWorkExperience');
    }

    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }
}
