<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CVBuilderController extends Controller
{
    /// profile

    public function step1_show()
    {
        return view('profile.edit')
            ->with([
                'profile' => Auth::user()->profile,
                'is_cvbuilder' => true,
            ]);
    }

    public function step1_save(Request $request)
    {
        $data = $request->validate(ProfileController::$validation);

        $profile = Auth::user()->profile;

        if($request->hasFile('avatar'))
        {
            $path = $request->file('avatar')->store('public');
            $profile->avatar_path = $path;
        }

        $profile->fill($data);
        $profile->save();

        return redirect(route('cv-builder.work-experience'));
    }

    /// work experience

    public function step2_show()
    {
        return view('profile.work-experience.edit')
            ->with([
                'profile' => Auth::user()->profile,
                'is_cvbuilder' => true,
            ]);
    }

    public function step2_save()
    {
        return redirect(route('cv-builder.references'));
    }

    /// references

    public function step3_show()
    {
        return view('profile.references.edit')
            ->with([
                'profile' => Auth::user()->profile,
                'is_cvbuilder' => true,
            ]);
    }

    public function step3_save()
    {
        return redirect(route('cv-builder.certifications'));
    }

    /// certifications

    public function step4_show()
    {
        return view('profile.certifications.edit')
            ->with([
                'profile' => Auth::user()->profile,
                'is_cvbuilder' => true,
            ]);
    }

    public function step4_save()
    {
        return redirect(route('profile'));
    }
}
