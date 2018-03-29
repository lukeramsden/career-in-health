<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('only.employer')->except('show');
    }

    static $validation = [
        'name' => 'required|string',
        'headline' => 'nullable|string|max:80',
        'location' => 'nullable|string|max:80',
        'description' => 'nullable|string|max:500',
        'avatar' => 'nullable|image|max:1024|dimensions:max_width=600,max_height=600,ratio=1'
    ];

    public function show(Company $company)
    {
        return view('profile.company.show')
            ->with([
                'company' => $company
            ]);
    }

    public function show_me()
    {
        return view('profile.company.show')
            ->with([
                'company' => Auth::user()->company
            ]);
    }

    public function edit()
    {
        return view('profile.company.edit')
            ->with([
                'company' => Auth::user()->company
            ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate($this::$validation);

        $company = Auth::user()->company;

        $old_avatar_path = $company->avatar_path;

        if($request->hasFile('avatar'))
        {
            $path = $request->file('avatar')->storePublicly('avatars');
            $company->avatar_path = $path;
            Storage::delete($old_avatar_path);
        }

        $company->fill($data);
        $company->save();

        return back()
            ->withInput()
            ->with([
                'status' => 'Profile updated!'
            ]);
    }
}
