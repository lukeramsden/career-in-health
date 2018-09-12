<?php

namespace App\Http\Controllers;

use App\JobListing;
use App\JobListingApplication;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobListingApplicationController extends Controller
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;

		$this->middleware('auth')->except('create');
		$this->middleware('user-type:employee')->except('update');
		$this->middleware('must-onboard');
	}

	protected function rules($custom = [])
	{
		return array_merge([
			'status' => 'nullable|integer',
			'notes'  => 'nullable|string|max:500',
		], $custom);
	}

	public function index()
	{
		return view('employee.view-applications')
			->with([
				'applications' =>
					Auth
						::user()
						->userable
						->applications()
						->with('employee', 'job_listing', 'job_listing.company', 'job_listing.jobRole')
						->orderBy('created_at', 'desc')
						->paginate(15),
			]);
	}

	public function show(JobListingApplication $application)
	{
		return view('employee.job-listing.view-application')
			->with([
				'application' => $application,
			]);
	}

	public function create(JobListing $jobListing)
	{
		if (Auth::guest())
		{
			session(['jobApplyId' => $jobListing->id]);
			return redirect(route('register'));
		}

		if (JobListingApplication::hasApplied(Auth::user()->userable, $jobListing))
		{
			toast()->error('You have already applied to this job!');
			return redirect(route('job-listing.show', [$jobListing]));
		}

		session()->keep('clickThrough');

		return view('employee.job-listing.apply')
			->with(['jobListing' => $jobListing]);
	}

	public function store(JobListing $jobListing)
	{
		if (JobListingApplication::hasApplied(Auth::user()->userable, $jobListing))
		{
			toast()->error('You have already applied to this job!');
			return redirect(route('job-listing.show', [$jobListing]));
		}

		$data = $this->request->validate(self::rules([
			'custom_cover_letter' => 'nullable|string|max:3000',
		]));

		$application = new JobListingApplication();
		$application->fill($data);
		$application->employee_id = Auth::user()->userable->id;
		$application->last_edited = Carbon::now();;
		$jobListing->applications()->save($application);

		switch (session()->get('clickThrough', 'search'))
		{
			case 'search':
				$jobListing->increment('search_conversions');
				break;
			case 'recommended':
				$jobListing->increment('recommended_conversions');
				break;
		}

		\Notification::send(
			$jobListing
				->company
				->users
				->map(returns('user')),
			new \App\Notifications\CompanyReceivedListingApplication($application)
		);

		toast()->success('Applied!');
		return redirect(route('job-listing.show', [$jobListing]));
	}

	/**
	 * @param JobListingApplication $application
	 *
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 * @throws \Exception
	 */
	public function update(JobListingApplication $application)
	{
		$user = Auth::user();
		// if editing status or notes
		if ($this->request->has('status') || $this->request->has('notes'))
		{
			// dont let non-owners edit status or notes for applications for jobListings they dont own
			if (!$user->isValidCompany() || $application->job_listing->company->id !== $user->userable->company->id)
			{
				if (ajax())
					return response()->json(['success' => false, 'message' => 'You must own the listing to update an application\'s status or notes.'], 401);

				toast()->error('You must own the listing to update an application\'s status or notes.');
				return back();
			}
			else
			{
				$data = $this->request->validate(self::rules([
					'custom_cover_letter' => 'nullable|string|max:3000',
				]));

				if (isset($data['status']))
					$application->status = $data['status'];

				if (isset($data['notes']))
					$application->notes = $data['notes'];
			}
		}
		else
			$application->fill($this->request->validate(self::rules([
				'custom_cover_letter' => 'nullable|string|max:3000',
			])));

		$application->last_edited = Carbon::now();
		$application->save();

		if (ajax())
			return response()->json(['success' => true]);

		toast()->success('Updated!');
		return back();
	}
}
