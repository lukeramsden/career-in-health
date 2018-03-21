<?php

namespace App\Http\Controllers;

use App\Models\ProfileWorkExperience;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileWorkExperienceController extends Controller
{
    static $validation = [
        'job_title' => 'required|string|max:80',
        'company_name' => 'required|string|max:80',
        'start_date' => 'required|date_format:Y-m-d|before:tomorrow',
        'end_date' => 'nullable|date_format:Y-m-d|after:start_date',
    ];

    public function edit()
    {
        return view('profile.work-experience.edit')
            ->with([
                'profile' => Auth::user()->profile,
                'isCvBuilder' => false,
            ]);
    }

    public function edit_single(ProfileWorkExperience $profileWorkExperience)
    {
        return view('profile.work-experience.edit_single')
            ->with([
                'work' => $profileWorkExperience,
                'isCvBuilder' => false
            ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this::$validation);

        $profileWorkExperience = new ProfileWorkExperience();
        $profileWorkExperience->fill($data);
        Auth::user()->profile->work()->save($profileWorkExperience);

        return back()
            ->with([
                'status' => 'Created!'
            ]);
    }

    public function update(Request $request, ProfileWorkExperience $profileWorkExperience)
    {
        $data = $request->validate($this::$validation);

        $profileWorkExperience->fill($data);
        $profileWorkExperience->save();

        if($request->query('isCvBuilder', false))
        {
            return redirect(route('cv-builder.work-experience'))
                ->with([
                    'status' => 'Updated!'
                ]);
        }

        return redirect(route('profile.work.edit'))
            ->with([
                'status' => 'Updated!'
            ]);
    }

    public function destroy(ProfileWorkExperience $profileWorkExperience)
    {
        try {
            $profileWorkExperience->delete();
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with([
                    'status' => 'Could not delete!'
                ]);
        }

        return back()
            ->with([
                'status' => 'Deleted!'
            ]);
    }
}
