<?php

namespace App\Http\Controllers;

use App\CompanyUser;
use App\CompanyUserInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyUserManagementController extends Controller
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;

		$this->middleware('auth');
		$this->middleware('must-onboard');
		$this->middleware('user-type:company');
		$this->middleware('company-user-permission-level:owner,manager');
	}

	public function show()
	{
		return view('company.manage-users')
			->with([
				'company' => Auth::user()->userable->company,
			]);
	}

	public function inviteUser()
	{
		$data = $this->request->validate([
			'email' => 'required|email|distinct|unique:users,email|unique:company_user_invites,email',
		]);

		$companyUser = Auth::user()->userable;
		$companyUser->company->invite(
			$data['email'],
			$companyUser->id
		);

		if (ajax())
			return response()->json(['success' => true], 200);

		toast()->success($data['email'].' has been invited!');
		return back();
	}

	public function cancelInvite(CompanyUserInvite $invite)
	{
		try
		{
			$invite->delete();
		} catch (\Exception $e)
		{
			debug($e);
			abort(500);
		}

		if (ajax())
			return response()->json(['success' => true], 200);

		toast()->success('Invite has been cancelled');
		return back();
	}

	public function removeUser(CompanyUser $companyUser)
	{
		$curCompanyUser = Auth::user()->userable;

		if (!$curCompanyUser->hasPermsOver($companyUser))
		{
			toast()->error('Access Denied');
			return back();
		}

		/*
		 * TODO: some sort of logic for removing users
		 * problem is that how do we handle company users without companies?
		 * need james' opinion
		 */

		toast()->warning('Work In Progress');
		return back();
	}

	public function updatePermissionForUser(CompanyUser $companyUser)
	{
		$curCompanyUser = Auth::user()->userable;

		if (!$curCompanyUser->hasPermsOver($companyUser))
		{
			toast()->error('Access Denied');
			return back();
		}

		toast()->warning('Work In Progress');
		return back();
	}
}
