<?php

namespace App\Http\Controllers;

use App\CompanyUser;
use App\User;
use App\CompanyUserInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CompanyUserInviteController extends Controller
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;

		$this->middleware('guest');
	}

	protected function rules($custom = [])
	{
		return array_merge([
			'first_name'  => 'required|string|max:255',
			'last_name'   => 'nullable|string|max:255',
			'password'    => 'required|string|min:6|confirmed',
			'terms'       => 'required'
		], $custom);
	}

	public function show($code)
	{
		$invite = CompanyUserInvite::whereAcceptCode($code)->first();

		if(!isset($invite))
			return abort(404, 'Invite not found.');

		return view('company-user.accept-invite')
			->with([
				'code'   => $code,
				'invite' => $invite,
			]);
	}

	/**
	 * @param CompanyUserInvite $invite
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 * @throws \Exception
	 */
	public function accept(CompanyUserInvite $invite)
	{
		$data = $this->request->validate(self::rules());

		$userable = new CompanyUser([
			'first_name' => $data['first_name'],
			'company_id' => $invite->company_id,
		]);
		$userable->save();

		$user                    = new User();
		$user->email             = $invite->email;
		$user->confirmed         = true;
		$user->confirmation_code = null;
		$user->password          = Hash::make($data['password']);

		$user->userable()->associate($userable);
		$user->save();

		DB
			::table('company_user_permissions')
			->insert([
				'company_id'       => $invite->company_id,
				'company_user_id'  => $user->userable_id,
				'permission_level' => 'standard',
			]);

		Auth::guard()->login($user);

		$invite->delete();

		return redirect(route('dashboard'));
	}
}
