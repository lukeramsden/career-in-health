<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap the application services.
   *
   * @return void
   */
  public function boot()
  {
	Blade::if('onboarding', function () {
	  return Auth::check() && Auth::user()->onboarding()->inProgress();
	});

	Blade::if('usertype', function ($types = '') {
	  if (Auth::check())
		foreach (explode(',', $types) as $type)
		  if ($type === Auth::user()->user_type_name)
			return true;

	  return false;
	});
  }

  /**
   * Register the application services.
   *
   * @return void
   */
  public function register()
  {
	//
  }
}
