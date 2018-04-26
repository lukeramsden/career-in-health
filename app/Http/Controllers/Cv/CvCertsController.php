<?php

namespace App\Http\Controllers\Cv;

use App\Cv\CvCert;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CvCertsController extends Controller
{
    /**
     * CvCertsController constructor.
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
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'file' => 'required|file|max:1024|mimes:pdf,jpg,png',
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

        $cert = new CvCert();
        $cert->cv_id = Auth::user()->cv->id;
        $cert->fill($data);
        $cert->save();

        if($request->ajax())
        {
            return response()->json(['success' => true, 'model' => $cert], 200);
        }

        toast()->success('Created');
        return back();
    }

    /**
     * Update model
     *
     * @param Request $request
     * @param CvCert $cert
     * @return mixed
     */
    public function update(Request $request, CvCert $cert)
    {
        $data = $request->validate(self::getValidationRules($request));

        if(!isset($data['end_date']))
            $data['end_date'] = null;

        $cert->fill($data);
        $cert->save();

        if(ajax())
        {
            return response()->json(['success' => true, 'model' => $cert], 200);
        }

        toast()->success('Updated');
        return back();
    }

    /**
     * Delete model
     *
     * @param CvCert $cert
     * @return mixed
     * @throws \Exception
     */
    public function destroy(CvCert $cert)
    {
        $cert->delete();

        if(ajax())
        {
            return response()->json(['success' => true], 200);
        }

        toast()->success('Deleted');
        return back();
    }
}