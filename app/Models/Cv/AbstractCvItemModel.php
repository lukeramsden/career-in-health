<?php

namespace App\Cv;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractCvItemModel extends Model
{
    protected $guarded = ['id', '_token'];
    protected $fillable = [];

    public function cv()
    {
        return $this->belongsTo(Cv::class, 'cv_id', 'id');
    }
}
