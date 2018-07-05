<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Onboard;

class RegisterOnboardingSteps
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 *
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (Auth::check())
		{
			$user = Auth::user();

			if ($user->isCompany())
			{
				Onboard::addStep('Fill Out Your Profile')
					   ->link(route('company-user.edit'))
					   ->cta('Complete')
					   ->completeIf(function (User $user)
					   {
						   return $user->userable->has_been_filled;
					   });

				Onboard::addStep('Create A Company')
					   ->link(route('company.create'))
					   ->cta('Create')
					   ->completeIf(function (User $user)
					   {
						   return $user->isValidCompany();
					   });

				Onboard::addStep('Add An Address')
					   ->link(route('address.create'))
					   ->cta('Add')
					   ->completeIf(function (User $user)
					   {
						   return $user->hasCreatedAddress();
					   });

				Onboard::addStep('Create An Advert')
					   ->link(route('advert.create'))
					   ->cta('Create')
					   ->completeIf(function (User $user)
					   {
						   return $user->hasCreatedAdvert();
					   });

			}
			elseif ($user->isEmployee())
			{
				Onboard::addStep('Fill Out Your Profile')
					   ->link(route('employee.edit'))
					   ->cta('Complete')
					   ->completeIf(function (User $user)
					   {
						   return $user->userable->has_been_filled;
					   });

				Onboard::addStep('Fill Out Your CV')
					   ->link(route('cv.builder'))
					   ->cta('Complete')
					   ->completeIf(function (User $user)
					   {
						   static $result = null;
						   if (is_null($result))
							   $result = $user->userable->cv->education()->count() > 0
								   || $user->userable->cv->workExperience()->count() > 0
								   || $user->userable->cv->certifications()->count() > 0
								   || $user->userable->cv->preferences->is_edited;
						   return $result;
					   });

				Onboard::addStep('Search For An Advert')
					   ->link(route('search', ['advanced' => true]))
					   ->cta('Search')
					   ->completeIf(function (User $user)
					   {
						   return $user->userable->times_searched > 0;
					   });
			}
		}

		return $next($request);
	}
}
