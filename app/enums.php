<?php

namespace App\Enum;

use ReflectionClass;

abstract class UserType
{
  const EMPLOYEE     = 1;
  const COMPANY_USER = 2;
  const ADMIN        = 3;

  /**
   * @return array
   */
  static function all()
  {
	try
	{
	  $oClass = new ReflectionClass(__CLASS__);
	} catch (\ReflectionException $e)
	{
	  abort(500);
	}
	return $oClass->getConstants();
  }
}