<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->middleware('auth');
        $this->middleware('user-type:company')->except('show');
        $this->middleware('must-onboard')->except(['create', 'store']);
    }

    protected function rules($custom = [])
    {
        return array_merge([
            'name'            => 'required|string|unique:companies',
            'usersToInvite'   => 'nullable|array',
            'usersToInvite.*' => 'nullable|email|distinct|unique:users,email',
            'avatar'          => 'nullable|image|max:1024|dimensions:max_width=600,max_height=600,ratio=1|mimes:jpg,jpeg,png',
            'remove_avatar'   => 'nullable|boolean',
            'location_id'     => 'required|integer|exists:locations,id',
            'about'           => 'nullable|string|max:500',
            'phone'           => 'nullable|string',
            'email'           => 'nullable|string',
        ], $custom);
    }

    public function show(Company $company)
    {
        return view('company.show')
            ->with([
                'company' => $company,
                'self' => false,
            ]);
    }

    public function showMe()
    {
        return view('company.show')
            ->with([
                'company' => Auth::user()->userable->company,
                'self' => true,
            ]);
    }

    public function edit()
    {
        return view('company.create')
            ->with([
                'company' => Auth::user()->userable->company,
                'edit' => true,
            ]);
    }

    public function update()
    {
        $user = Auth::user();
        $company = $user->userable->company;

        $data = $this->request->validate(
            self::rules([
                    'name' => ['required', 'string', Rule::unique('companies')->ignore($company->id)],
                ]
            ));

        $company->fill($data);

        if(isset($data['remove_avatar']) && $data['remove_avatar'])
        {
            Storage::delete($company->avatar);
            $company->avatar = null;
        } else if($this->request->hasFile('avatar'))
        {
            $path = $this->request->file('avatar')->storePublicly('avatars');
            Storage::delete($company->avatar);
            $company->avatar = $path;
        }

        $company->save();

        toast()->success('Saved!');
        return back();
    }

    public function create()
    {
        return view('company.create')
            ->with([
                'company' => new Company(),
                'edit' => false,
            ]);
    }

    public function store()
    {
        $data = $this->request->validate(self::rules());

        $user = Auth::user();

        $company = new Company();
        $company->created_by_user_id = $user->id;
        $company->fill($data);

        if($this->request->hasFile('avatar'))
        {
            $path = $this->request->file('avatar')->storePublicly('avatars');
            $company->avatar = $path;
        }

        $company->save();

        $user->userable->company_id = $company->id;
        $user->userable->save();

        if(Auth::user()->onboarding()->inProgress())
            return redirect(Auth::user()->onboarding()->nextUnfinishedStep()->link);

        return redirect(route('dashboard'));
    }

    public function showApplications()
	{
		return view('company.view-applications')
			->with([
				'applications' => Auth::user()->userable->company->applications
			]);
	}
}
