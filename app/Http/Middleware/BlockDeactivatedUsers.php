<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class BlockDeactivatedUsers
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
		if (!$request->is('logout') && Auth::check() && Auth::user()->deactivated)
		{
			if(ajax())
				abort(403, 'Your account has been deactivated.');

			return response(view('deactivated'));
		}

		return $next($request);
	}
}
