<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CompanyProfileController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->middleware('auth');
        $this->middleware('only.employer')->except('show');
    }

    public function show(Company $company)
    {
        return view('company.profile.show')
            ->with([
                'company' => $company
            ]);
    }

    public function showMe()
    {
        return view('company.profile.show')
            ->with([
                'company' => Auth::user()->company
            ]);
    }

    public function edit()
    {
        return view('company.profile.edit')
            ->with([
                'company' => Auth::user()->company
            ]);
    }

    public function update()
    {
        $company = Auth::user()->company;
        $data = $this->request->validate([
            // TODO: should we allow companies to change their name? probably yes
            'name' => ['required', 'string', 'max:40', Rule::unique('companies')->ignore($company->id)],
            'headline' => 'nullable|string|max:80',
            'location' => 'nullable|string|max:80',
            'description' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|max:1024|dimensions:max_width=600,max_height=600,ratio=1|mimes:jpg,jpeg,png',
            'phone' => 'nullable|string|max:40',
            'contact_email' => 'nullable|email|max:80',
            'remove_avatar' => 'nullable|boolean'
        ]);

        if($data['remove_avatar'])
        {
            Storage::delete($company->avatar_path);
            $company->avatar_path = null;
        } else if($this->request->hasFile('avatar'))
        {
            $path = $this->request->file('avatar')->storePublicly('avatars');
            Storage::delete($company->avatar_path);
            $company->avatar_path = $path;
        }

        $company->fill($data);
        $company->save();

        toast()->success('Profile Updated!');
        return back();
    }
}
