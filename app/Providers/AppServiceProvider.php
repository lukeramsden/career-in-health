<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
	if ($this->app->environment('local'))
	  $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);

	Schema::defaultStringLength(191);
	Cashier::useCurrency('gbp');

	Relation::morphMap([
	  'App\Employee',
	  'App\CompanyUser',
	  'App\Admin',
	  'App\Advertising\Advertiser',
	]);
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
	//
  }
}
