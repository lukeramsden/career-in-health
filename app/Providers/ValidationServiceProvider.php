<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap the application services.
   *
   * @return void
   */
  public function boot()
  {
	Validator::extend('greater_than_field', function ($attribute, $value, $parameters, $validator) {
	  $min_field = $parameters[0];
	  $data      = $validator->getData();
	  $min_value = $data[$min_field];
	  return $value > $min_value;
	});

	Validator::replacer('greater_than_field', function ($message, $attribute, $rule, $parameters) {
	  return str_replace(':field', __('validation.attributes.' . $parameters[0]), $message);
	});

	Validator::extend('less_than_field', function ($attribute, $value, $parameters, $validator) {
	  $min_field = $parameters[0];
	  $data      = $validator->getData();
	  $min_value = $data[$min_field];
	  return $value < $min_value;
	});

	Validator::replacer('less_than_field', function ($message, $attribute, $rule, $parameters) {
	  return str_replace(':field', __('validation.attributes.' . $parameters[0]), $message);
	});

	Validator::extend('postcode', function ($attribute, $value, $parameters, $validator) {
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
