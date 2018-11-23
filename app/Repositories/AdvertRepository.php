<?php

namespace App\Repositories;

use App\Advertising\HomePageAdvert;

class AdvertRepository
{
  /**
   * AdvertRepository constructor.
   */
  public function __construct()
  {

  }

  /**
   * @return HomePageAdvert|null
   */
  public static function homepage()
  {
	if ((bool)config('app.advertising') !== true)
	  return null;

	$advert = HomePageAdvert::whereHas('advert', function ($q) {
	  $q->whereActive(true);
	})->inRandomOrder()->first();

	if ($advert !== null)
	  $advert->increment('stat_views');

	return $advert;
  }

}