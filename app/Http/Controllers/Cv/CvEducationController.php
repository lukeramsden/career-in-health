<?php

namespace App\Http\Controllers\Cv;

use App\Cv\CvEducation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CvEducationController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->middleware('auth');
        $this->middleware('user-type:employee');
    }

    protected function rules()
    {
        return [
            'degree'         => 'required|string|max:150',
            'school_name'    => 'required|string|max:150',
            'field_of_study' => 'required|string|max:150',
            'location'       => 'required|string|max:150',
            'start_date'     => 'required|date',
            'end_date'       => 'nullable|date'
        ];
    }

    public function store()
    {
        $data = $this->request->validate(self::rules());

        $education = new CvEducation();
        $education->cv_id = Auth::user()->cv->id;
        $education->fill($data);
        $education->save();

        if(ajax())
            return response()->json(['success' => true, 'model' => $education], 200);

        toast()->success('Created');
        return back();
    }

    public function update(CvEducation $education)
    {
        $data = $this->request->validate(self::rules());

        if(!isset($data['end_date']))
            $data['end_date'] = null;

        $education->fill($data);
        $education->save();

        if(ajax())
            return response()->json(['success' => true, 'model' => $education], 200);

        toast()->success('Updated');
        return back();
    }

    /**
     * @throws \Exception
     */
    public function destroy(CvEducation $education)
    {
        $education->delete();

        if(ajax())
            return response()->json(['success' => true], 200);

        toast()->success('Deleted');
        return back();
    }
}