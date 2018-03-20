<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    static $validation = [
        'first_name' => 'required|string|max:40',
        'last_name' => 'nullable|string|max:40',
        'headline' => 'nullable|string|max:80',
        'location' => 'nullable|string|max:80',
        'description' => 'nullable|string|max:255',
        'avatar' => 'nullable|image|max:1024|dimensions:max_width=600,max_height=600,ratio=1',
        'job_types' => 'required|array|min:1',
        'job_types.*' => 'integer|distinct|exists:job_types,id'
    ];

    public function show()
    {
        return view('profile')
            ->with([
                'profile' => Auth::user()->profile
            ]);
    }

    public function edit()
    {
        return view('profile_edit')
            ->with([
                'profile' => Auth::user()->profile,
                'action' => route('profile.update'),
            ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate($this::$validation);

        $profile = Auth::user()->profile;

        $old_avatar_path = $profile->avatar_path;

        if($request->hasFile('avatar'))
        {
            $path = $request->file('avatar')->storePublicly('avatars');
            $profile->avatar_path = $path;
            Storage::delete($old_avatar_path);
        }

        $profile->fill($data);
        $profile->jobTypes()->sync($data['job_types']);
        $profile->save();

        return back()
            ->withInput()
            ->with([
                'status' => 'Profile updated!'
            ]);
    }
}
