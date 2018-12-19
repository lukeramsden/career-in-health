<?php

namespace App\Http\Controllers\Cv;

use App\Cv\Cv;
use App\Cv\CvPreferences;
use App\Http\Controllers\Controller;
use App\JobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PreferencesController extends Controller
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
	  'job_role' => 'nullable|integer|exists:job_roles,id',
	  'setting'  => ['nullable', Rule::in(array_keys(JobListing::$settings))],
	  'type'     => ['nullable', Rule::in(array_keys(JobListing::$types))],

	  'salary_number' => 'nullable|required_with:salary_type|numeric|min:0|max:150000',
	  'salary_type'   => ['nullable', 'required_with:salary_number', Rule::in(array_keys(CvPreferences::$salaryTypes))],

	  'willing_to_relocate' => 'nullable|boolean',
	];
  }

  public function update()
  {
	$data = $this->request->validate(self::rules());

	$data['willing_to_relocate'] = $data['willing_to_relocate'] ?? false;

	/** @var Cv $cv */
	$cv = Auth::user()->userable->cv;

	if ($cv->preferences()->exists())
	{
	  $preferences = $cv->preferences;
	  $preferences->fill($data);
	  $preferences->save();
	}
	else
	{
	  $preferences = new CvPreferences();
	  $preferences->fill($data);
	  $preferences->save();
	  $cv->preferences_id = $preferences->id;
	  $cv->save();
	}

	if (ajax())
	  return response()->json(['success' => true, 'model' => $preferences], 200);

	toast()->success('Updated');
	return back();
  }
}
