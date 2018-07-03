<?php

namespace App\Cv;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractCvItemModel extends Model
{
    protected $fillable = [];
    protected $appends = ['is_edited'];

    public function getIsEditedAttribute()
    {
         return $this->attributes['is_edited'] = ($this->created_at != $this->updated_at) ? true : false;
    }

    public function cv()
    {
        return $this->belongsTo(Cv::class, 'cv_id', 'id');
    }
}
