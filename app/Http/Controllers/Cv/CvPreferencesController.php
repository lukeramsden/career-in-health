<?php

namespace App\Http\Controllers\Cv;

use App\Advert;
use App\Cv\Cv;
use App\Cv\CvPreferences;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CvPreferencesController extends Controller
{
    /**
     * CvPreferencesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('only.employee');
    }

    /**
     * Get validation rules for request
     *
     * @param Request $request
     * @return array
     */
    protected function rules(Request $request)
    {
        return [
            'job_role' => 'nullable|integer|exists:job_roles,id',
            'setting' => ['nullable', Rule::in(array_keys(Advert::$settings))],
            'type' => ['nullable', Rule::in(array_keys(Advert::$types))],
            'salary_number' => 'nullable|required_with:salary_type|numeric|min:0|max:150000',
            'salary_type' => ['nullable', 'required_with:salary_number', Rule::in(array_keys(CvPreferences::$salaryTypes))],
            'willing_to_relocate' => 'nullable|boolean'
        ];
    }

    /**
     * Update model
     *
     * @param Request $request
     * @param Cv $cv
     * @return mixed
     */
    public function update(Request $request)
    {
        $data = $request->validate(self::rules($request));

        if(!isset($data['willing_to_relocate']))
            $data['willing_to_relocate'] = false;

        $cv = Auth::user()->cv;

        if($cv->preferences()->exists())
        {
            $preferences = $cv->preferences;
            $preferences->fill($data);
            $preferences->save();
        } else
        {
            $preferences = new CvPreferences();
            $preferences->fill($data);
            $preferences->save();
            $cv->preferences_id = $preferences->id;
            $cv->save();
        }

        if(ajax())
        {
            return response()->json(['success' => true, 'model' => $preferences], 200);
        }

        toast()->success('Updated');
        return back();
    }
}
