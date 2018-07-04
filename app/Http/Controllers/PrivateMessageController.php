<?php

namespace App\Http\Controllers;

use App\Advert;
use App\PrivateMessage;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrivateMessageController extends Controller
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;

		$this->middleware('auth');
		$this->middleware('mustOnboard');
		$this->middleware(function ($request, Closure $next)
		{
			$message = $request->route('message');

			if ($message && Auth::check() && !$message->sentTo(Auth::user()))
			{
				toast()->error('Cannot change read status of a message you didn\'t receive.');
				return back();
			}

			return $next($request);
		})
			->only('markAsRead', 'markAsUnread')
		;
	}

	public function show(Advert $advert)
	{
		return view('account.private-message.show')
			->with([
				'advert' => $advert,
			]);
	}

	public function store()
	{
		$data = $this->request->validate(self::rules());

		$message = new PrivateMessage();

		$user     = Auth::user();
		$userable = $user->userable;

		if (isset($data->to_employee_id))
		{
			$message->direction   = 'to_employee';
			$message->employee_id = $data->to_employee_id;
			$message->company_id  = $userable->company_id;
		}
		elseif (isset($data->to_company_id))
		{
			$message->direction   = 'to_company';
			$message->company_id  = $data->to_company_id;
			$message->employee_id = $userable->employee_id;
		}

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
