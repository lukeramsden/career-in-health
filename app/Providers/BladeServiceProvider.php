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

	Blade::directive('vue', function ($expression) {
	  $name = str_replace("'", '', $expression);
	  return "<?php echo \"<div id=\\\"vue-$name\\\"><$name></$name></div>\"; ?>";
	});

	Blade::directive('vueWhen', function ($expression) {
	  $args = explode(',', $expression);
	  $when = trim($args[0]);
	  $name = trim(str_replace("'", '', $args[1]));

	  return "<?php if($when) echo \"<div id=\\\"vue-$name\\\"><$name></$name></div>\"; ?>";
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
