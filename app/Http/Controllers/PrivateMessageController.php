<?php

namespace App\Http\Controllers;

use App\Advert;
use App\Employee;
use App\PrivateMessage;
use Closure;
use Illuminate\Http\Request;
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
		$this->middleware(function ($request, Closure $next)
		{
			$message = $request->route('message');

			if ($message && Auth::check() && !$message->wasSentTo(Auth::user()))
			{
				toast()->error('Cannot change read status of a message you didn\'t receive.');
				return back();
			}

			return $next($request);
		})->only('markAsRead', 'markAsUnread');
		$this->middleware('user-type:employee')->only('showForAdvert');
		$this->middleware('user-type:company')->only('showForAdvertAndEmployee');
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
				::with('company', 'advert.address', 'advert.address.location')
				->whereEmployeeId($userable->id)
				->select('company_id', 'advert_id', DB::raw('MAX(`created_at`) as `created_at`'))
				->orderByDesc('created_at')
				->groupBy('employee_id', 'advert_id', 'company_id');

			return view('account.private-message.index')
				->with([
					'messages' => $messages->get()
				]);
		} elseif ($user->isValidCompany()) {
			$messages = PrivateMessage
				::whereCompanyId($userable->company->id)
				->select('employee_id', 'advert_id', DB::raw('MAX(`created_at`) as `created_at`'))
				->orderByDesc('created_at')
				->groupBy('employee_id', 'company_id', 'advert_id');

			return view('account.private-message.index')
				->with([
					'messages' => $messages->get()
				]);
		}

		return abort(500);
	}

	public function showForAdvert(Advert $advert)
	{
		$user     = Auth::user();
		$userable = $user->userable;

		$messages = PrivateMessage
			::whereAdvertId($advert->id)
			->whereEmployeeId($userable->id)
			->whereCompanyId($advert->company->id);

		(clone $messages)
			->whereDirection('to_employee')
			->each(function ($v, $k)
			{
				$v->markAsRead();
			});

		return view('account.private-message.show')
			->with([
				'messages' => $messages->get(),
				'advert'   => $advert,
			]);
	}

	public function showForAdvertAndEmployee(Advert $advert, Employee $employee)
	{
		$messages = PrivateMessage
			::whereAdvertId($advert->id)
			->whereEmployeeId($employee->id)
			->whereCompanyId($advert->company->id);

		(clone $messages)
			->whereDirection('to_company')
			->each(function ($v, $k)
			{
				$v->markAsRead();
			});

		return view('account.private-message.show')
			->with([
				'messages' => $messages->get(),
				'advert'   => $advert,
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

	protected function rules()
	{
		return [
			'body'           => 'required|string|max:1000',
			'advert_id'      => 'required|integer|exists:adverts,id',
			'to_employee_id' => 'required_without:to_company_id|integer|exists:employees,id',
			'to_company_id'  => 'required_without:to_employee_id|integer|exists:companies,id',
		];
	}
}
