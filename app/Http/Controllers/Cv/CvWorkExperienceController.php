<?php

namespace App\Http\Controllers\Cv;

use App\Cv\CvWorkExperience;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CvWorkExperienceController extends Controller
{
    /**
     * CvWorkExperienceController constructor.
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
            'job_title' => 'required|string|max:150',
            'company_name' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
            'location' => 'required|string|max:150',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date'
        ];
    }

    /**
     * Store model
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $data = $request->validate(self::getValidationRules($request));

        $cvEducation = new CvWorkExperience();
        $cvEducation->fill($data);
        $cvEducation->save();

        if($request->ajax())
        {
            return response()->json(['success' => true], 200);
        }

        toast()->success('Created');
        return back();
    }

    /**
     * Update model
     *
     * @param Request $request
     * @param CvWorkExperience $cvWorkExperience
     * @return mixed
     */
    public function update(Request $request, CvWorkExperience $cvWorkExperience)
    {
        $data = $request->validate(self::getValidationRules($request));

        $cvWorkExperience->fill($data);
        $cvWorkExperience->save();

        if(ajax())
        {
            return response()->json(['success' => true], 200);
        }

        toast()->success('Updated');
        return back();
    }

    /**
     * Delete model
     *
     * @param CvWorkExperience $cvWorkExperience
     * @return mixed
     * @throws \Exception
     */
    public function destroy(CvWorkExperience $cvWorkExperience)
    {
        $cvWorkExperience->delete();

        if(ajax())
        {
            return response()->json(['success' => true], 204);
        }

        toast()->success('Deleted');
        return back();
    }
}