<?php

namespace App\Http\Controllers;

use App\CompanyUser;
use App\User;
use App\UserInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserInviteController extends Controller
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
	        	'accept_code' => 'required|uuid|exists:user_invites',
				'first_name'  => 'required|string|max:255',
				'last_name'   => 'nullable|string|max:255',
				'password'    => 'required|string|min:6|confirmed',
				'terms'       => 'required'
			], $custom);
	    }

	public function show($code)
	{
		$invite = UserInvite::whereAcceptCode($code)->first();

		if(!isset($invite))
			return abort(404, 'Invite not found.');

		return view('user.accept-invite')
			->with([
				'code' => $code,
			]);
	}

	public function accept()
	{
		$data = $this->request->validate(self::rules());

		$invite = UserInvite::whereAcceptCode($data['code'])->first();

		if(!isset($invite))
			return abort(404, 'Invite not found.');

		$userable = new CompanyUser();
		$userable->first_name = $data['first_name'];
		if(isset($data['last_name']))
			$userable->last_name = $data['last_name'];
		$userable->save();

		$user = new User();
		$user->email = $invite->email;
		$user->confirmed = true;
		$user->confirmation_code = null;
		$user->password = Hash::make($data['password']);
		$user->userable()->associate($userable);
		$user->save();
	}
}
