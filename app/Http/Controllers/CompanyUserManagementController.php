<?php

namespace App\Http\Controllers;

use App\CompanyUser;
use App\CompanyUserInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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

		toast()->success($data['email'] . ' has been invited!');
		return back();
	}

	public function cancelInvite(CompanyUserInvite $invite)
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

	public function makeOwner(CompanyUser $companyUser)
	{
		if (!Auth::user()->userable->ownsCompany())
		{
			toast()->error('Access Denied');
			return back();
		}

		if (Auth::user()->userable->company->makeOwner($companyUser))
		{
			toast()->success("{$companyUser->full_name} has been made owner");
			return redirect(route('company.manage-users.show'));
		}
		else
		{
			toast()->error('Error');
			return back();
		}
	}

	public function updatePermissionForUser(CompanyUser $companyUser)
	{
		if (!Auth::user()->userable->hasPermsOver($companyUser))
		{
			toast()->error('Access Denied');
			return back();
		}

		$data = $this->request->validate([
			'new_permission_level' => ['required', Rule::in(['standard', 'manager'])],
		]);

		DB
			::table('company_user_permissions')
			->where('company_user_id', $companyUser->id)
			->update([
				'permission_level' => $data['new_permission_level'],
			]);


		toast()->success("{$companyUser->full_name} has been updated.");
		return back();
	}

	public function remindInvite(CompanyUserInvite $invite)
	{
		$invite->remind();

		return back();
	}

	public function activateUser(CompanyUser $companyUser)
	{
		if (!Auth::user()->userable->ownsCompany())
		{
			toast()->error('Access Denied');
			return back();
		}

		$companyUser->activate();

		toast()->success("{$companyUser->full_name} has been activated.");
		return back();
	}

	public function deactivateUser(CompanyUser $companyUser)
	{
		if (!Auth::user()->userable->ownsCompany())
		{
			toast()->error('Access Denied');
			return back();
		}

		$companyUser->deactivate();

		toast()->success("{$companyUser->full_name} has been deactivated.");
		return back();

	}
}
