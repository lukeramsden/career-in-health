<?php

namespace App\Http\Controllers\Cv;

use App\Cv\CvCert;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
    protected function getValidationRules(Request $request, bool $creatingNew)
    {
        $rules = [
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
        ];

        return $creatingNew ?
              array_merge($rules, ['file' => 'required|file|max:1024|mimes:pdf,jpg,jpeg,png'])
            : $rules;
    }

    /**
     * Store model
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $data = $request->validate(self::getValidationRules($request, true));

        $certification = new CvCert();

        $path = $request->file('file')->store('certs');

        if($path) {
            $certification->file = $path;
        } else {
            if(ajax()) {
                return response()->json(['success' => false, 'message' => 'File invalid'], 400);
            } else {
                toast()->error('File invalid');
                return back();
            }
        }

        $certification->cv_id = Auth::user()->cv->id;
        $certification->fill($data);
        $certification->save();

        if($request->ajax())
            return response()->json(['success' => true, 'model' => $certification], 200);

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
    public function update(Request $request, CvCert $certification)
    {
        $data = $request->validate(self::getValidationRules($request, false));

        if(!isset($data['end_date']))
            $data['end_date'] = null;

        $certification->fill($data);
        $certification->save();

        if(ajax())
            return response()->json(['success' => true, 'model' => $certification], 200);

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
    public function destroy(CvCert $certification)
    {
        $certification->delete();

        if(ajax())
            return response()->json(['success' => true], 200);

        toast()->success('Deleted');
        return back();
    }
}