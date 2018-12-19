<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Events\CreatedPrivateMessage;
use App\JobListing;
use App\Notifications\ReceivedPrivateMessage;
use App\PrivateMessage;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
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

  public function rules()
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
	  ->whereCompanyId($company->id)
	  ->get();

	if (ajax())
	  return response()->json([
		'success' => true,
		'models'  => $messages,
	  ], 200);

	return view('account.private-message.show')
	  ->with([
		'messages'   => $messages,
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
	  ->whereCompanyId($company->id)
	  ->get();

	if (ajax())
	  return response()->json([
		'success' => true,
		'models'  => $messages,
	  ], 200);

	return view('account.private-message.show')
	  ->with([
		'messages'   => $messages,
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

	  if (PrivateMessage::where([
		'employee_id'    => $userable->id,
		'company_id'     => $data['to_company_id'],
		'direction'      => 'to_employee',
		'job_listing_id' => $data['job_listing_id'],
	  ])->doesntExist())
	  {
		if (ajax())
		  return response()->json(['success' => false, 'message' => 'Only a company can initiate a conversation.'], 403);

		toast()->error('Only a company can initiate a conversation.');
		return back();
	  }
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
   * @param JobListing    $listing
   * @param Employee|null $employee
   *
   * @throws \Exception
   */
  public function markAllAsRead(JobListing $jobListing, Employee $employee = null)
  {
	$user     = Auth::user();
	$userable = $user->userable;
	$read_at  = now();

	$lambdaMarkNotificationsAsRead = function ($ids) use ($user) {
	  $notification_ids = collect([]);

	  $save = function () use ($notification_ids) {
		DatabaseNotification
		  ::whereIn('id', $notification_ids->toArray())
		  ->update(['read_at' => now()]);
	  };

	  foreach (DatabaseNotification
				 ::where('type', ReceivedPrivateMessage::class)
				 ->where('notifiable_id', $user->id)
				 ->whereNull('read_at')
				 ->cursor() as $notification)
	  {
		if ($notification->data &&
		  $ids->contains(optional($notification->data)['message_id']))
		  $notification_ids->push($notification->id);

		if ($notification_ids->count() > 50)
		  $save();
	  }

	  $save();
	};

	if ($user->isEmployee())
	{
	  $q = PrivateMessage
		::whereJobListingId($jobListing->id)
		->whereEmployeeId($userable->id)
		->whereCompanyId($jobListing->company->id)
		->whereDirection('to_employee');

	  (clone $q)->update([
		'read'    => true,
		'read_at' => $read_at,
	  ]);

	  $lambdaMarkNotificationsAsRead($q->pluck('id'));

	  if (ajax())
		return response()->json(['success' => true, 'read_at' => $read_at]);

	  return back();
	}
	elseif ($user->isValidCompany())
	{
	  if (is_null($employee))
		abort(400, 'Employee cannot be null');

	  $q = PrivateMessage
		::whereJobListingId($jobListing->id)
		->whereEmployeeId($employee->id)
		->whereCompanyId($userable->company_id)
		->whereDirection('to_company');

	  (clone $q)->update([
		'read'    => true,
		'read_at' => $read_at,
	  ]);

	  $lambdaMarkNotificationsAsRead($q->pluck('id'));

	  if (ajax())
		return response()->json(['success' => true, 'read_at' => $read_at]);

	  return back();
	}
	else return abort(401, 'Invalid user type.');
  }
}
