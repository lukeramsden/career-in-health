<?php

namespace App\Http\Controllers\Cv;

use App\Cv\CvWorkExperience;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    protected function rules(Request $request)
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
        $data = $request->validate(self::rules($request));

        $workExperience = new CvWorkExperience();
        $workExperience->cv_id = Auth::user()->cv->id;
        $workExperience->fill($data);
        $workExperience->save();

        if($request->ajax())
        {
            return response()->json(['success' => true, 'model' => $workExperience], 200);
        }

        toast()->success('Created');
        return back();
    }

    /**
     * Update model
     *
     * @param Request $request
     * @param CvWorkExperience $workExperience
     * @return mixed
     */
    public function update(Request $request, CvWorkExperience $workExperience)
    {
        $data = $request->validate(self::rules($request));

        if(!isset($data['end_date']))
            $data['end_date'] = null;

        $workExperience->fill($data);
        $workExperience->save();

        if(ajax())
        {
            return response()->json(['success' => true, 'model' => $workExperience], 200);
        }

        toast()->success('Updated');
        return back();
    }

    /**
     * Delete model
     *
     * @param CvWorkExperience $workExperience
     * @return mixed
     * @throws \Exception
     */
    public function destroy(CvWorkExperience $workExperience)
    {
        $workExperience->delete();

        if(ajax())
        {
            return response()->json(['success' => true], 200);
        }

        toast()->success('Deleted');
        return back();
    }
}