<?php

namespace App\Http\Controllers\Cv;


use App\Cv\CvEducation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CvEducationController extends Controller
{
    /**
     * CvEducationController constructor.
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
            'degree' => 'required|string',
            'school_name' => 'required|string',
            'field_of_study' => 'required|string',
            'location' => 'required|string',
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

        $cvEducation = new CvEducation();
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
     * @param CvEducation $cvEducation
     * @return mixed
     */
    public function update(Request $request, CvEducation $cvEducation)
    {
        $data = $request->validate(self::getValidationRules($request));

        $cvEducation->fill($data);
        $cvEducation->save();

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
     * @param CvEducation $cvEducation
     * @return mixed
     * @throws \Exception
     */
    public function destroy(CvEducation $cvEducation)
    {
        $cvEducation->delete();

        if(ajax())
        {
            return response()->json(['success' => true], 204);
        }

        toast()->success('Deleted');
        return back();
    }
}