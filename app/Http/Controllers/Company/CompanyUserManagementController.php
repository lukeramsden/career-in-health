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
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function show()
	{
		$this->authorize('view', CompanyUser::class);

		return view('company.manage-users')
			->with([
				'company' => Auth::user()->userable->company,
			]);
	}

	/**
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function inviteUser()
	{
		$this->authorize('inviteUser', CompanyUser::class);

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

	/**
	 * @param CompanyUserInvite $invite
	 *
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function cancelInvite(CompanyUserInvite $invite)
	{
		$this->authorize('inviteUser', CompanyUser::class);

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
	 * @param CompanyUser $companyUser
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function makeOwner(CompanyUser $companyUser)
	{
		$this->authorize('changeOwner', CompanyUser::class);

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

	/**
	 * @param CompanyUser $companyUser
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function updatePermissionForUser(CompanyUser $companyUser)
	{
		$this->authorize('changePermissionLevel', $companyUser);

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

	/**
	 * @param CompanyUserInvite $invite
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function remindInvite(CompanyUserInvite $invite)
	{
		$this->authorize('inviteUser', CompanyUser::class);

		$invite->remind();

		return back();
	}

	/**
	 * @param CompanyUser $companyUser
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function activateUser(CompanyUser $companyUser)
	{
		$this->authorize('activateUser', $companyUser);

		$companyUser->activate();

		toast()->success("{$companyUser->full_name} has been activated.");
		return back();
	}

	/**
	 * @param CompanyUser $companyUser
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function deactivateUser(CompanyUser $companyUser)
	{
		$this->authorize('deactivateUser', $companyUser);

		$companyUser->deactivate();

		toast()->success("{$companyUser->full_name} has been deactivated.");
		return back();

	}
}
