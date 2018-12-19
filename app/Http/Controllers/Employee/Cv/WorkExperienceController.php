<?php

namespace App\Http\Controllers\Cv;

use App\Cv\CvWorkExperience;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkExperienceController extends Controller
{
  protected $request;

  public function __construct(Request $request)
  {
	$this->request = $request;

	$this->middleware(['auth', 'user-type:employee']);
  }

  public function rules()
  {
	return [
	  'job_title'    => 'required|string|max:150',
	  'company_name' => 'required|string|max:150',
	  'description'  => 'nullable|string|max:500',
	  'location'     => 'required|string|max:150',
	  'start_date'   => 'required|date',
	  'end_date'     => 'nullable|date',
	];
  }

  public function store()
  {
	$data = $this->request->validate(self::rules());

	$workExperience = new CvWorkExperience();

	$workExperience->cv_id = Auth::user()->userable->cv->id;
	$workExperience->fill($data);
	$workExperience->save();

	if (ajax())
	  return response()->json(['success' => true, 'model' => $workExperience], 200);

	toast()->success('Created');
	return back();
  }

  public function update(CvWorkExperience $workExperience)
  {
	$data = $this->request->validate(self::rules());

	$workExperience->fill($data);
	$workExperience->save();

	if (ajax())
	  return response()->json(['success' => true, 'model' => $workExperience], 200);

	toast()->success('Updated');
	return back();
  }

  /**
   * @throws \Exception
   */
  public function destroy(CvWorkExperience $workExperience)
  {
	$workExperience->delete();

	if (ajax())
	  return response()->json(['success' => true], 200);

	toast()->success('Deleted');
	return back();
  }
}