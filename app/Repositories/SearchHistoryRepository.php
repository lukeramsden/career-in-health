<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class SearchHistoryRepository
{
  /**
   * AdvertRepository constructor.
   */
  public function __construct()
  {

  }

  /**
   * @return \Illuminate\Database\Query\Builder
   * @throws \Exception
   */
  public static function find()
  {
	return DB::table('search_history')
			 ->where('searcher', static::getUuid());
  }

  /**
   * @return string
   * @throws \Exception
   */
  public static function getUuid()
  {
	if (Auth::check())
	  $uuid = Uuid::uuid5(Uuid::NAMESPACE_OID, Auth::user()->id)->toString();
	elseif (session()->has('uuid'))
	  $uuid = session()->get('uuid');
	else
	{
	  $uuid = Uuid::uuid4()->toString();
	  session()->put('uuid', $uuid);
	}

	return $uuid;
  }

}