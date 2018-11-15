<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap the application services.
   *
   * @return void
   */
  public function boot()
  {
	View::composer('*', function ($view) {
	  if (Auth::check())
	  {
		$user = Auth::user();
		$view->with('currentUser', $user);
		$view->with('userType', $user->user_type_name);
	  }
	  else
	  {
		$view->with('currentUser', null);
		$view->with('userType', null);
	  }
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
