<?php

namespace App\Http\Controllers\Cv;

use App\Cv\CvCert;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CvCertsController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->middleware('auth');
        $this->middleware('only.employee');
    }

    protected function rules(bool $creatingNew)
    {
        $rules = [
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date',
        ];

        if($creatingNew)
            $rules['file'] = 'required|file|max:1024|mimes:pdf,jpg,jpeg,png';

        return $rules;
    }

    public function store()
    {
        $data = $this->request->validate(self::rules(true));

        $certification = new CvCert();

        $path = $this->request->file('file')->store('certs');

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

        if(ajax())
            return response()->json(['success' => true, 'model' => $certification], 200);

        toast()->success('Created');
        return back();
    }

    public function update(CvCert $certification)
    {
        $data = $this->request->validate(self::rules(false));

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