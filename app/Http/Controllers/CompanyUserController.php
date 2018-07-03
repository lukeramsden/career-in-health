<?php

namespace App\Http\Controllers;

use App\CompanyUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyUserController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->middleware('auth');
        $this->middleware('user-type:company')->except('show');
    }

    protected function rules()
    {
        return [
            'first_name'    => 'required|string',
            'last_name'     => 'nullable|string',
            'avatar'        => 'nullable|image|max:1024|dimensions:max_width=600,max_height=600,ratio=1|mimes:jpg,jpeg,png',
            'remove_avatar' => 'nullable|boolean',
        ];
    }

    public function show(CompanyUser $companyUser)
    {
        return view('company-user.show')
            ->with([
                'companyUser' => $companyUser,
                'self' => false,
            ]);
    }

    public function showMe()
    {
        return view('company-user.show')
            ->with([
                'companyUser' => Auth::user()->userable,
                'self' => true,
            ]);
    }

    public function edit()
    {
        return view('company-user.edit')
            ->with([
                'companyUser' => Auth::user()->userable,
            ]);
    }

    public function update()
    {
        $data = $this->request->validate(self::rules());
        $companyUser = Auth::user()->userable;

        if(isset($data['remove_avatar']) && $data['remove_avatar'])
        {
            Storage::delete($companyUser->avatar);
            $companyUser->avatar = null;
        } else if($this->request->hasFile('avatar'))
        {
            $path = $this->request->file('avatar')->storePublicly('avatars');
            Storage::delete($companyUser->avatar);
            $companyUser->avatar = $path;
        }

        $companyUser->fill($data);
        $companyUser->has_been_filled = true;
        $companyUser->save();

        if(ajax())
            return response()->json(['success' => true, 'model' => $companyUser], 200);

        toast()->success('Profile updated!');
        return back();
    }
}
