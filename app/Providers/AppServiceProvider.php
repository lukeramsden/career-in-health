<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
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
		if ($this->app->environment() !== 'production')
			$this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);

		Schema::defaultStringLength(191);

		Cashier::useCurrency('gbp');

		Validator::extend('greater_than_field', function ($attribute, $value, $parameters, $validator)
		{
			$min_field = $parameters[0];
			$data      = $validator->getData();
			$min_value = $data[$min_field];
			return $value > $min_value;
		});

		Validator::replacer('greater_than_field', function ($message, $attribute, $rule, $parameters)
		{
			return str_replace(':field', __('validation.attributes.' . $parameters[0]), $message);
		});

		Validator::extend('less_than_field', function ($attribute, $value, $parameters, $validator)
		{
			$min_field = $parameters[0];
			$data      = $validator->getData();
			$min_value = $data[$min_field];
			return $value < $min_value;
		});

		Validator::replacer('less_than_field', function ($message, $attribute, $rule, $parameters)
		{
			return str_replace(':field', __('validation.attributes.' . $parameters[0]), $message);
		});

		Validator::extend('postcode', function ($attribute, $value, $parameters, $validator)
		{
			$value                 = trim($value);
			$validation_expression = '/^'
				. '([Gg][Ii][Rr] 0[Aa]{2})|'
				. '((([A-Za-z][0-9]{1,2})|'
				. '(([A-Za-z][A-Ha-hJ-Yj-y][0-9]{1,2})|'
				. '(([A-Za-z][0-9][A-Za-z])|'
				. '([A-Za-z][A-Ha-hJ-Yj-y][0-9]?[A-Za-z]))))\s?[0-9][A-Za-z]{2})'
				. '$/i';

			return preg_match($validation_expression, $value);
		});

		Blade::if('onboarding', function ()
		{
			return Auth::check() && Auth::user()->onboarding()->inProgress();
		});

		Blade::if('usertype', function ($types = '')
		{
			if (Auth::check())
			{
				$user     = Auth::user();
				$userable = $user->userable;

				foreach (explode(',', $types) as $type)
				{
					switch ($type)
					{
						case 'employee':
							if ($userable instanceof \App\Employee)
								return true;
							break;
						case 'company':
							if ($userable instanceof \App\CompanyUser)
								return true;
							break;
						case 'admin':
							if ($userable instanceof \App\Admin)
								return true;
							break;
						case 'advertiser':
							if ($userable instanceof \App\Advertiser)
								return true;
							break;
					}
				}
			}

			return false;
		});
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
