<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CompanyUserPermissionLevel
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
	public function handle($request, Closure $next, ...$levels)
	{
		if (Auth::check() && ($user = Auth::user())->isValidCompany())
			foreach ($levels as $level)
			{
				if ($level === 'owner' ?
					  $user->userable->id === $user->userable->company->owner_id
					: $user->userable->permission_level === $level)
					return $next($request);
			}

		if (ajax())
			return response()->json(['message' => 'Access Denied'], 403);

		toast()->error('Access Denied.');
		return redirect(route('dashboard'));
	}
}
