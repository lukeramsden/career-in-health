<?php

namespace App\Http\Controllers\Admin;

use App\Advertiser;
use App\AdvertiserInvite;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdvertiserManagementController extends Controller
{
	protected $request;

	/**
	 * AdvertiserManagementController constructor.
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;

		$this->middleware('auth');
		$this->middleware('user-type:admin');
	}

	/**
	 * @param array $custom
	 *
	 * @return array
	 */
	protected function rules($custom = [])
	{
		return array_merge([

		], $custom);
	}

	public function show()
	{
		return view('admin.manage-advertisers')
			->with([
				'advertisers' => Advertiser::all(),
				'invites'     => AdvertiserInvite::all(),
			]);
	}

	/**
	 * @param Advertiser $advertiser
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function activateUser(Advertiser $advertiser)
	{
		$advertiser->activate();

		toast()->success("{$advertiser->name} has been activated.");
		return back();
	}

	/**
	 * @param Advertiser $advertiser
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function deactivateUser(Advertiser $advertiser)
	{
		$advertiser->deactivate();

		toast()->success("{$advertiser->name} has been deactivated.");
		return back();

	}

	/**
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function inviteUser()
	{
		$data = $this->request->validate([
			'email' => 'required|email|distinct|unique:users,email|unique:advertiser_invites,email',
		]);

		$invite        = new AdvertiserInvite();
		$invite->email = $data['email'];
		$invite->save();
		$invite->remind();

		if (ajax())
			return response()->json(['success' => true], 200);

		toast()->success($data['email'] . ' has been invited!');
		return back();
	}

	/**
	 * @param AdvertiserInvite $invite
	 *
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function cancelInvite(AdvertiserInvite $invite)
	{
		try
		{
			$invite->delete();
		} catch (\Exception $e)
		{
			abort(500);
		}

		if (ajax())
			return response()->json(['success' => true], 200);

		toast()->success('Invite has been cancelled');
		return back();
	}

	/**
	 * @param AdvertiserInvite $invite
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function remindInvite(AdvertiserInvite $invite)
	{
		$invite->remind();
		return back();
	}
}
