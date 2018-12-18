<?php

namespace App\Http\Middleware;

use Closure;

class AdvertisingEnabled
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  \Closure                 $next
   *
   * @return mixed
   * @throws \Exception
   */
  public function handle($request, Closure $next)
  {
	if ((bool)config('app.advertising') !== true)
	  throw new \Exception('Advertising is not enabled at this time.');;

	return $next($request);
  }
}
