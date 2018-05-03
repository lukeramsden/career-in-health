<?php

namespace App\Http\Controllers\Cv;

use App\Cv\CvEducation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'degree' => 'required|string|max:150',
            'school_name' => 'required|string|max:150',
            'field_of_study' => 'required|string|max:150',
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

        $education = new CvEducation();
        $education->cv_id = Auth::user()->cv->id;
        $education->fill($data);
        $education->save();

        if(ajax())
        {
            return response()->json(['success' => true, 'model' => $education], 200);
        }

        toast()->success('Created');
        return back();
    }

    /**
     * Update model
     *
     * @param Request $request
     * @param CvEducation $education
     * @return mixed
     */
    public function update(Request $request, CvEducation $education)
    {
        $data = $request->validate(self::getValidationRules($request));

        if(!isset($data['end_date']))
            $data['end_date'] = null;

        $education->fill($data);
        $education->save();

        if(ajax())
        {
            return response()->json(['success' => true, 'model' => $education], 200);
        }

        toast()->success('Updated');
        return back();
    }

    /**
     * Delete model
     *
     * @param CvEducation $education
     * @return mixed
     * @throws \Exception
     */
    public function destroy(CvEducation $education)
    {
        $education->delete();

        if(ajax())
        {
            return response()->json(['success' => true], 200);
        }

        toast()->success('Deleted');
        return back();
    }
}