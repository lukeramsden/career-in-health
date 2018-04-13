<?php

namespace App\Http\Controllers\Cv;

use App\Advert;
use App\Cv\Cv;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
    protected function getValidationRules(Request $request)
    {
        return [
            'job_type_id' => 'nullable|integer|exists:job_types,id',
            'setting' => ['nullable', Rule::in(array_keys(Advert::$settings))],
            'type' => ['nullable', Rule::in(array_keys(Advert::$types))],
            'salary' => 'nullable|integer|min:0|max:150000',
            'willing_to_relocate' => 'required|boolean'
        ];
    }

    /**
     * Update model
     *
     * @param Request $request
     * @param Cv $cv
     * @return mixed
     */
    public function update(Request $request, Cv $cv)
    {
        $data = $request->validate(self::getValidationRules($request));

        $cv->fill($data);
        $cv->save();

        if(ajax())
        {
            return response()->json(['success' => true], 200);
        }

        toast()->success('Updated');
        return back();
    }
}
