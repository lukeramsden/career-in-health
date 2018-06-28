<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->middleware('auth');
        $this->middleware('user-type:company');
        $this->middleware('mustOnboard')->except(['create', 'store']);
    }

    public function create()
    {
        return view('company.create');
    }

    public function store()
    {
        $data = $this->request->validate([
            'name'            => 'required|string|unique:companies',
            'usersToInvite'   => 'nullable|array',
            'usersToInvite.*' => 'nullable|email|distinct|unique:users,email',
            'avatar'          => 'nullable|image|max:1024|dimensions:max_width=600,max_height=600,ratio=1|mimes:jpg,jpeg,png',
            'location_id'     => 'required|integer|exists:locations,id',
            'about'           => 'nullable|string|max:500',
        ]);

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
}
