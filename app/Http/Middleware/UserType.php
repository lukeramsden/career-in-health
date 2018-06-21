<?php

namespace App\Http\Middleware;

use App\Admin;
use App\CompanyUser;
use App\Employee;
use App\Enum\UserType as UserTypeEnum;
use Closure;
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
    public function handle($request, Closure $next, $type)
    {
        $user = Auth::user();
        $userable = $user->userable;

        switch($type)
        {
            case 'employee':
                if($userable instanceof Employee)
                    return $next($request);
                break;
            case 'company':
                if($userable instanceof CompanyUser)
                    return $next($request);
                break;
            case 'admin':
                if($userable instanceof Admin)
                    return $next($request);
                break;
            default:
                abort(500, '$type does not match any user type');
        }

        toast()->error('Access Denied.');
        return back();
    }
}
