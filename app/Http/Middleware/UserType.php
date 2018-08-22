<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;

class UserType
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 *
	 * @return mixed
	 */
	public function handle($request, \Closure $next, ...$types)
	{

		if (Auth::check())
		{
			$user     = Auth::user();
			$userable = $user->userable;

			foreach ($types as $type)
				switch ($type)
				{
					case 'employee':
						if ($userable instanceof \App\Employee)
							return $next($request);
						break;
					case 'company':
						if ($userable instanceof \App\CompanyUser)
							return $next($request);
						break;
					case 'admin':
						if ($userable instanceof \App\Admin)
							return $next($request);
						break;
					case 'advertiser':
						if ($userable instanceof \App\Advertising\Advertiser)
							return $next($request);
						break;
					default:
						abort(500, '$type does not match any user type');
				}
		}

		if (ajax())
			return response()->json(['message' => 'Access Denied'], 403);

		toast()->error('Access Denied.');
		return back();
	}
}
