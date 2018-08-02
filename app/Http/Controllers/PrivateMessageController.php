<?php

namespace App\Http\Controllers;

use App\JobListing;
use App\Employee;
use App\PrivateMessage;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PrivateMessageController extends Controller
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;

		$this->middleware('auth');
		$this->middleware('must-onboard');
		$this->middleware('can:sendMessages,'.PrivateMessage::class);
		$this->middleware('can:changeReadStatus,privateMessage')->only('markAsRead', 'markAsUnread');
		$this->middleware('user-type:employee')->only('showForJobListing');
		$this->middleware('user-type:company')->only('showForJobListingAndEmployee');
	}

	protected function rules()
	{
		return [
			'body'           => 'required|string|max:1000',
			'job_listing_id' => 'required|integer|exists:jobListings,id',
			'to_employee_id' => 'required_without:to_company_id|integer|exists:employees,id',
			'to_company_id'  => 'required_without:to_employee_id|integer|exists:companies,id',
		];
	}

	/**
	 * @throws \Exception
	 */
	public function index()
	{
		$user = Auth::user();
		$userable = $user->userable;

		if($user->isEmployee()) {
			$messages = PrivateMessage
				::with('company', 'job_listing.address', 'job_listing.address.location')
				->findMany(
					PrivateMessage
						::whereEmployeeId($userable->id)
						->select(DB::raw('MAX(`id`) as `id`'), 'company_id', 'job_listing_id', DB::raw('MAX(`created_at`) as `created_at`'))
						->orderByDesc('created_at')
						->groupBy('employee_id', 'job_listing_id', 'company_id')
						->get()
						->pluck('id')
				)->reverse();
		} elseif ($user->isValidCompany()) {
			$messages = PrivateMessage
				::with('job_listing.address', 'job_listing.address.location', 'employee', 'employee.location')
				->findMany(
					PrivateMessage
						::whereCompanyId($userable->company->id)
						->select(DB::raw('MAX(`id`) as `id`'), 'employee_id', 'job_listing_id', DB::raw('MAX(`created_at`) as `created_at`'))
						->orderByDesc('created_at')
						->groupBy('employee_id', 'job_listing_id', 'company_id')
						->get()
						->pluck('id')
				)->reverse();
		}

		$messages = collect($messages);
		$result = view('account.private-message.index');
		if($messages->count() > 0)
		{
			$perPage = 10;
			$currentPage = $this->request->query('page', 1);

			$paginator = new LengthAwarePaginator(
				array_slice(
					$messages->all(),
					$perPage * ($currentPage - 1),
					$perPage
				),
				$messages->count(),
				$perPage,
				$currentPage,
    			[ 'path' => '/'.$this->request->path() ]
			);
			$result->with([
				'messages' => $paginator
			]);
		}

		return $result;
	}

	public function showForJobListing(JobListing $jobListing)
	{
		$user     = Auth::user();
		$userable = $user->userable;

		$messages = PrivateMessage
			::whereJobListingId($jobListing->id)
			->whereEmployeeId($userable->id)
			->whereCompanyId($jobListing->company->id);

		(clone $messages)
			->whereDirection('to_employee')
			->each(function ($v, $k)
			{
				$v->markAsRead();
			});

		return view('account.private-message.show')
			->with([
				'messages' => $messages->get(),
				'job_listing'   => $jobListing,
			]);
	}

	public function showForJobListingAndEmployee(JobListing $jobListing, Employee $employee)
	{
		$messages = PrivateMessage
			::whereJobListingId($jobListing->id)
			->whereEmployeeId($employee->id)
			->whereCompanyId($jobListing->company->id);

		(clone $messages)
			->whereDirection('to_company')
			->each(function ($v, $k)
			{
				$v->markAsRead();
			});

		return view('account.private-message.show')
			->with([
				'messages' => $messages->get(),
				'job_listing'   => $jobListing,
				'employee' => $employee,
			]);
	}

	public function store()
	{
		$data = $this->request->validate(self::rules());

		$message = new PrivateMessage();

		$user     = Auth::user();
		$userable = $user->userable;

		if (isset($data['to_employee_id']))
		{
			$message->direction   = 'to_employee';
			$message->employee_id = $data['to_employee_id'];
			$message->company_id  = $userable->company_id;
		}
		elseif (isset($data['to_company_id']))
		{
			$message->direction   = 'to_company';
			$message->company_id  = $data['to_company_id'];
			$message->employee_id = $userable->id;
		}
		else abort(500);

		$message->fill($data);
		$message->save();

		if (ajax())
			return response()->json(['success' => true, 'model' => $message], 200);

		toast()->success('Message sent successfully');
		return back();
	}
}
