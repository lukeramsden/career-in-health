<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;

class UserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next, $type)
    {
        $user = Auth::user();
        $userable = $user->userable;

        switch($type)
        {
            case 'employee':
                if($userable instanceof \App\Employee)
                    return $next($request);
                break;
            case 'company':
                if($userable instanceof \App\CompanyUser)
                    return $next($request);
                break;
            case 'admin':
                if($userable instanceof \App\Admin)
                    return $next($request);
                break;
            default:
                abort(500, '$type does not match any user type');
        }

        toast()->error('Access Denied.');
        return back();
    }
}
