<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CVBuilderController extends Controller
{
    /// profile

    public function step1_show()
    {
        return view('profile.edit')
            ->with([
                'profile' => Auth::user()->profile,
                'isCvBuilder' => true,
            ]);
    }

    public function step1_save(Request $request)
    {
        $data = $request->validate(ProfileController::$validation);

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

        return redirect(route('cv-builder.work-experience'));
    }

    /// work experience

    public function step2_show()
    {
        return view('profile.work-experience.edit')
            ->with([
                'profile' => Auth::user()->profile,
                'isCvBuilder' => true,
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
                'isCvBuilder' => true,
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
                'isCvBuilder' => true,
            ]);
    }

    public function step4_save(Request $request)
    {
        if ($request->session()->has('apply_to_job_id')) {
            return redirect(route('advert.apply.create', ['advert' => session('apply_to_job_id')]));
        }

        return redirect(route('profile.me'));
    }
}
