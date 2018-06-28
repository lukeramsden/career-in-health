<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class RedirectToNextOnboardStep
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Request::method()
        // not redirecting between company 1 and 2 steps
        // if logged in and onboarding is in progress
        if(Auth::check() && ($onboardManager = Auth::user()->onboarding())->inProgress()) {
            // get step matching current fullURL
            $step = collect($onboardManager->steps())->first(function ($step) {
                return Request::fullUrlIs($step->link);
            });

            // if step exists and is complete
            if($step && $step !== $onboardManager->nextUnfinishedStep())
                // redirect to next route
                return redirect($onboardManager->nextUnfinishedStep()->link);
        }

        return $next($request);
    }
}
