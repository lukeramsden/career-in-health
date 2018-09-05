<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
	protected $request;

	/**
	 * NotificationController constructor.
	 *
	 * @param Request $request
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;

		$this->middleware('auth');
	}

	public function index()
	{
		return view('notifications.index')
			->with([
				'notifications' => Auth::user()
									   ->notifications()
									   ->orderByRaw('-read_at ASC')
									   ->paginate(20),
			]);
	}

	public function markAllAsRead()
	{
		Auth::user()->unreadNotifications()->update(['read_at' => now()]);

		if (ajax())
			return response()->json(['success' => true], 200);

		return back();
	}

	public function markAllAsUnread()
	{
		Auth::user()->readNotifications()->update(['read_at' => null]);

		if (ajax())
			return response()->json(['success' => true], 200);

		return back();
	}
}
