<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
  protected $request;

  public function __construct(Request $request)
  {
	$this->request = $request;

	$this->middleware('auth')->except('show');
	$this->middleware('user-type:company')->except('show');
	$this->middleware('must-onboard')->except(['create', 'store']);
  }

  public function show(Company $company)
  {
	if (ajax())
	  return response()->json([
		'success' => true,
		'model'   => $company,

	  ], 200);
	return view('company.show')
	  ->with([
		'company'   => $company,
		'self'      => false,
		'addresses' => $company
		  ->addresses()
		  ->orderByDesc('created_at')
		  ->paginate(5),
	  ]);
  }

  public function showMe()
  {
	$company = Auth::user()->userable->company;
	return view('company.show')
	  ->with([
		'company'   => $company,
		'self'      => true,
		'addresses' => $company
		  ->addresses()
		  ->orderByDesc('created_at')
		  ->paginate(5),
	  ]);
  }

  public function edit()
  {
	return view('company.create')
	  ->with([
		'company' => Auth::user()->userable->company,
		'edit'    => true,
	  ]);
  }

  /**
   * @return \Illuminate\Http\RedirectResponse
   * @throws \Illuminate\Auth\Access\AuthorizationException
   */
  public function update()
  {
	$user    = Auth::user();
	$company = $user->userable->company;

	$this->authorize('update', $company);

	$data = $this->request->validate(self::rules([
	  'name' => [
		'required',
		'string',
		Rule::unique('companies')->ignore($company->id),
	  ],
	]));

	$company->fill($data);

	if (isset($data['remove_avatar']) && $data['remove_avatar'])
	{
	  Storage::delete($company->avatar);
	  $company->avatar = null;
	}
	elseif ($this->request->hasFile('avatar'))
	{
	  $path = $this->request->file('avatar')->storePublicly('avatars');
	  Storage::delete($company->avatar);
	  $company->avatar = $path;
	}

	$company->save();

	toast()->success('Saved!');
	return back();
  }

  protected function rules($custom = [])
  {
	return array_merge([
	  'name'            => 'required|string|unique:companies',
	  'usersToInvite'   => 'nullable|array',
	  'usersToInvite.*' => 'nullable|email|distinct|unique:users,email|unique:company_user_invites,email',
	  'avatar'          => 'nullable|image|max:1024|dimensions:max_width=600,max_height=600,ratio=1|mimes:jpg,jpeg,png',
	  'remove_avatar'   => 'nullable|boolean',
	  'location_id'     => 'required|integer|exists:locations,id',
	  'about'           => 'nullable|string|max:500',
	  'phone'           => 'nullable|string',
	  'email'           => 'nullable|string',
	], $custom);
  }

  public function create()
  {
	return view('company.create')
	  ->with([
		'company' => new Company(),
		'edit'    => false,
	  ]);
  }

  /**
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function store()
  {
	$data = $this->request->validate(self::rules());

	$user = Auth::user();

	$company           = new Company();
	$company->owner_id = $user->userable->id;
	$company->fill($data);

	if ($this->request->hasFile('avatar'))
	{
	  $path            = $this->request->file('avatar')->storePublicly('avatars');
	  $company->avatar = $path;
	}

	$company->save();

	$user->userable->company_id = $company->id;
	$user->userable->save();

	DB
	  ::table('company_user_permissions')
	  ->insert([
		'company_id'       => $company->id,
		'company_user_id'  => $user->userable_id,
		'permission_level' => 'owner',
	  ]);

	if (isset($data['usersToInvite']))
	  foreach ($data['usersToInvite'] as $email)
		$company->invite($email);

	if (Auth::user()->onboarding()->inProgress())
	  return redirect(Auth::user()->onboarding()->nextUnfinishedStep()->link);

	return redirect(route('dashboard'));
  }

  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   * @throws \Illuminate\Auth\Access\AuthorizationException
   */
  public function showApplications()
  {
	$company = Auth::user()->userable->company;
	$this->authorize('update', $company);

	return view('company.view-applications')
	  ->with([
		'applications' => $company
		  ->applications
		  ->load('employee', 'job_listing'),
	  ]);
  }

  /**
   * @return \Illuminate\Http\JsonResponse
   * @throws \Illuminate\Auth\Access\AuthorizationException
   */
  public function getApplications()
  {
	/** @var Company $company */
	$company = Auth::user()->userable->company;
	$this->authorize('update', $company);

	return response()->json([
	  'success' => true,
	  'models'  => $company
		->applications
		->load('employee', 'job_listing'),
	], 200);
  }

  /**
   * @return \Illuminate\Http\JsonResponse
   * @throws \Illuminate\Auth\Access\AuthorizationException
   */
  public function getAddresses()
  {
	/** @var Company $company */
	$company = Auth::user()->userable->company;
	$this->authorize('update', $company);

	return response()->json([
	  'success' => true,
	  'models'  => $company->addresses,
	], 200);
  }
}
