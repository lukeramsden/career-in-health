<?php

namespace App\Http\Controllers;

use App\ProfileWorkExperience;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileWorkExperienceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('only.employee');
    }

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

        toast()->success('Created!');

        return back();
    }

    public function update(Request $request, ProfileWorkExperience $profileWorkExperience)
    {
        $data = $request->validate($this::$validation);

        $profileWorkExperience->fill($data);
        $profileWorkExperience->save();

        if($request->query('isCvBuilder', false))
        {
            toast()->success('Updated!');
            return redirect(route('cv-builder.work-experience'));
        }

        toast()->success('Updated');
        return redirect(route('profile.work.edit'));
    }

    public function destroy(ProfileWorkExperience $profileWorkExperience)
    {
        try {
            $profileWorkExperience->delete();
        } catch (Exception $e) {
            toast()->error('Could not delete');
            return back()
                ->withInput();
        }

        toast()->success('Deleted!');

        return back();
    }
}
