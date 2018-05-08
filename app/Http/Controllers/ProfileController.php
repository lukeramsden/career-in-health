<?php

namespace App\Http\Controllers;

use App\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('only.employee')->except('show');
        $this->middleware(function($request, Closure $next) {
            $user = $request->route('user');

            if(isset($user))
                if($user->isCompany())
                    return redirect(route('company.show', ['company' => $user->company]));

            return $next($request);
        })->only('show');
    }

    static $validation = [
        'first_name' => 'required|string|max:40',
        'last_name' => 'nullable|string|max:40',
        'phone' => 'nullable|string|max:40',
        'headline' => 'nullable|string|max:80',
        'location' => 'nullable|string|max:80',
        'description' => 'nullable|string|max:1000',
        'avatar' => 'nullable|image|max:1024|dimensions:max_width=600,max_height=600,ratio=1|mimes:jpg,jpeg,png',
        'job_roles' => 'nullable|array|min:1',
        'job_roles.*' => 'integer|distinct|exists:job_roles,id',
        'remove_avatar' => 'nullable|boolean'
    ];

    public function show(User $user)
    {
        return view('profile.show')
            ->with([
                'profile' => $user->profile
            ]);
    }

    public function show_me()
    {
        return view('profile.show')
            ->with([
                'profile' => Auth::user()->profile
            ]);
    }

    public function edit()
    {
        return view('profile.edit')
            ->with([
                'profile' => Auth::user()->profile,
            ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate(self::$validation);

        $profile = Auth::user()->profile;

        if(isset($data['remove_avatar']) && $data['remove_avatar'])
        {
          Storage::delete($profile->avatar_path);
          $profile->avatar_path = null;
        }
        else if($request->hasFile('avatar'))
        {
            $path = $request->file('avatar')->storePublicly('avatars');
            Storage::delete($profile->avatar_path);
            $profile->avatar_path = $path;
        }

        $profile->fill($data);

        if(isset($data['job_roles']))
            $profile->jobRoles()->sync($data['job_roles']);

        $profile->save();

        toast()
            ->success('Profile updated!')
            ->info('<a href=\'' . route('profile.show.me') . '\' class=\'btn btn-action btn-sm mt-1\'>View Profile</a>');

        return back();
    }
}
