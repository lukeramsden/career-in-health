<?php

namespace App\Cv;

use App\Model\CustomModel as Model;

abstract class AbstractCvItemModel extends Model
{
  protected $fillable = [];
  protected $appends  = ['is_edited'];
  protected $touches  = ['cv'];
  protected $hidden   = ['cv_id'];

  public function getIsEditedAttribute()
  {
	return $this->created_at != $this->updated_at;
  }

  public function cv()
  {
	return $this->belongsTo(Cv::class, 'cv_id', 'id');
  }
}
