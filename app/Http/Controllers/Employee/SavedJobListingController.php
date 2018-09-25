<?php

namespace App\Http\Controllers;

use App\Employee;
use App\JobListing;
use App\SavedJobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedJobListingController extends Controller
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;

		$this->middleware('auth');
		$this->middleware('user-type:employee');
	}

	protected function rules()
	{
		return [
			'employee_id'    => 'required|integer|exists:employees,id',
			'job_listing_id' => 'required|integer|exists:job_listings,id',
		];
	}

	public function index()
	{
		/** @var Employee $employee */
		$employee = Auth::user()->userable;
		return view('employee.job-listing.view-saved')
			->with([
				'jobListings' => SavedJobListing
					::with('jobListing', 'jobListing.address', 'jobListing.company', 'jobListing.jobRole')
					->whereEmployeeId($employee->id)
					->orderBy('created_at', 'desc')
					->get(),
			]);
	}

	public function add(JobListing $jobListing)
	{
		/** @var Employee $employee */
		$employee = Auth::user()->userable;
		$employee->saveJobListing($jobListing);

		if(ajax())
			return response()->json(['success' => true], 200);

		toast()->success('Listing added to your saved list');
		return back();
	}

	public function remove(JobListing $jobListing)
	{
		/** @var Employee $employee */
		$employee = Auth::user()->userable;
		$employee->unsaveJobListing($jobListing);

		if(ajax())
			return response()->json(['success' => true], 200);

		toast()->success('Listing removed from your saved list');
		return back();
	}
}
