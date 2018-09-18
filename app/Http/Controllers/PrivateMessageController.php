<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Events\CreatedPrivateMessage;
use App\JobListing;
use App\PrivateMessage;
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
		$this->middleware('can:sendMessages,' . PrivateMessage::class);
		$this->middleware('can:changeReadStatus,privateMessage')->only('markAsRead', 'markAsUnread');
		$this->middleware('user-type:employee')->only('showForJobListing');
		$this->middleware('user-type:company')->only('showForJobListingAndEmployee');
	}

	protected function rules()
	{
		return [
			'body'           => 'required|string|max:1000',
			'job_listing_id' => 'required|integer|exists:job_listings,id',
			'to_employee_id' => 'required_without:to_company_id|integer|exists:employees,id',
			'to_company_id'  => 'required_without:to_employee_id|integer|exists:companies,id',
		];
	}

	/**
	 * @throws \Exception
	 */
	public function index()
	{
		$user     = Auth::user();
		$userable = $user->userable;

		if ($user->isEmployee())
		{
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
		}
		elseif ($user->isValidCompany())
		{
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
		$result   = view('account.private-message.index');
		if ($messages->count() > 0)
		{
			$perPage     = 10;
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
				['path' => '/' . $this->request->path()]
			);
			$result->with([
				'messages' => $paginator,
			]);
		}

		return $result;
	}

	public function showForJobListing(JobListing $jobListing)
	{
		$user     = Auth::user();
		$employee = $user->userable;
		$company  = $jobListing->company;

		$messages = PrivateMessage
			::whereJobListingId($jobListing->id)
			->whereEmployeeId($employee->id)
			->whereCompanyId($company->id);

		(clone $messages)
			->whereDirection('to_employee')
			->each(function ($v, $k)
			{
				$v->markAsRead();
			});

		return view('account.private-message.show')
			->with([
				'messages'   => $messages->get(),
				'jobListing' => $jobListing,
				'employee'   => $employee,
				'company'    => $company,
			]);
	}

	public function showForJobListingAndEmployee(JobListing $jobListing, Employee $employee)
	{
		$company  = $jobListing->company;
		$messages = PrivateMessage
			::whereJobListingId($jobListing->id)
			->whereEmployeeId($employee->id)
			->whereCompanyId($company->id);

		(clone $messages)
			->whereDirection('to_company')
			->each(function ($v, $k)
			{
				$v->markAsRead();
			});

		return view('account.private-message.show')
			->with([
				'messages'   => $messages->get(),
				'jobListing' => $jobListing,
				'employee'   => $employee,
				'company'    => $company,
			]);
	}

	/**
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 * @throws \Exception
	 */
	public function store()
	{
		$data = $this->request->validate(self::rules());

		$message = new PrivateMessage();

		$user     = Auth::user();
		$userable = $user->userable;

		$is_to_employee = isset($data['to_employee_id']) && $user->isValidCompany();
		$is_to_company  = isset($data['to_company_id']) && $user->isEmployee();
		if ($is_to_employee)
		{
			$message->direction   = 'to_employee';
			$message->employee_id = $data['to_employee_id'];
			$message->company_id  = $userable->company_id;
		}
		elseif ($is_to_company)
		{
			$message->direction   = 'to_company';
			$message->company_id  = $data['to_company_id'];
			$message->employee_id = $userable->id;
		}
		else abort(500);

		$message->fill($data);
		$message->save();

		if ($is_to_employee)
		{
			$message->employee->user->notify(new \App\Notifications\ReceivedPrivateMessage($message));
		}
		elseif ($is_to_company)
		{
			\Notification::send(
				$message
					->company
					->users
					->map(returns('user')),
				new \App\Notifications\ReceivedPrivateMessage($message)
			);
		}

		broadcast(new CreatedPrivateMessage($message))->toOthers();

		if (ajax())
			return response()->json(['success' => true, 'model' => $message], 200);

		toast()->success('Message sent successfully');
		return back();
	}

	/**
	 * @param PrivateMessage $message
	 *
	 * @return string
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 * @throws \Throwable
	 */
	public function render(PrivateMessage $message)
	{
		$this->authorize('view', $message);
		return $message->render();
	}
}
