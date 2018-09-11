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

				Onboard::addStep('Set Preferences')
					   ->link(action('UserController@editNotificationPreferences'))
					   ->cta('Complete')
					   ->completeIf(function (User $user)
					   {
						   return ($pref = $user->notificationPreferences()->first())
							   && !is_null($pref->created_at) && !is_null($pref->updated_at)
							   && $pref->created_at != $pref->updated_at;
					   });

				Onboard::addStep('Add An Address')
					   ->link(route('address.create'))
					   ->cta('Add')
					   ->completeIf(function (User $user)
					   {
						   return $user->hasCreatedAddress();
					   });

				Onboard::addStep('Create A Listing')
					   ->link(route('job-listing.create'))
					   ->cta('Create')
					   ->completeIf(function (User $user)
					   {
						   return $user->hasCreatedJobListing();
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

				Onboard::addStep('Set Preferences')
					   ->link(action('UserController@editNotificationPreferences'))
					   ->cta('Complete')
					   ->completeIf(function (User $user)
					   {
						   return ($pref = $user->notificationPreferences()->first())
							   && !is_null($pref->created_at) && !is_null($pref->updated_at)
							   && $pref->created_at != $pref->updated_at;
					   });

				Onboard::addStep('Search')
					   ->link(route('search'))
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
