<?php

namespace App\Http\Middleware;

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
        dd(Auth::user()->userable);
        switch($type)
        {
            case UserTypeEnum::EMPLOYEE:
                {
                    break;
                }
            case UserTypeEnum::COMPANY_USER:
                {
                    break;
                }
            case UserTypeEnum::ADMIN:
                {
                    break;
                }

        }

        return $next($request);
    }
}
