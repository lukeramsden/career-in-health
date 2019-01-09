<?php

namespace App\Http\Controllers\Cv;

use App\Cv\Cv;
use App\Cv\CvCertification;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BuilderController extends Controller
{
  protected $request;

  public function __construct(Request $request)
  {
	$this->request = $request;

	$this->middleware(['auth', 'user-type:employee']);
  }

  public function show()
  {
	return view('employee.cv.edit');
  }

  public function getFull()
  {
	return response()->json(
	  Auth::user()
		->userable
		->cv()
		->with(['education', 'workExperience', 'certifications', 'preferences'])
		->first(),
	  201
	);
  }

  /**
   * Takes one big CV object and syncs the CV to match it
   *
   * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
   */
  public function save()
  {
	// Basic validation
	$this->request->validate([
	  'cv'                 => 'required|array',
	  'cv.preferences'     => 'nullable|array',
	  'cv.work_experience' => 'nullable|array',
	  'cv.education'       => 'nullable|array',
	  'cv.certifications'  => 'nullable|array',
	  'cv.certifications.*.',
	]);

	// Base vars
	/** @var User $user */
	/** @var object $requestCv */
	/** @var Cv $cv */
	$user      = Auth::user();
	$requestCv = (object)$this->request->get('cv');
	$cv        = $user->userable->cv;

	try
	{
	  DB::beginTransaction();

	  // Update preferences
	  if ($this->request->has('cv.preferences'))
	  {
		$clonedRequest = clone $this->request;
		$clonedRequest->replace($requestCv->preferences);
		// Use the real preferences controller to handle validation and whatnot
		$controller = new PreferencesController($clonedRequest);
		$controller->update();
	  }

	  if ($this->request->has('cv.work_experience'))
	  {
		// Validate all entries in WorkExp with controllers rules and a fake request
		foreach ($requestCv->work_experience as $wexp)
		{
		  $clonedRequest = (clone $this->request)->replace($wexp);
		  $clonedRequest->validate((new WorkExperienceController($clonedRequest))->rules());
		}

		// Use App\CustomModel's HasManySyncable function to update existing entries,
		// create new ones and delete unused ones. Really cool stuff I pasted from SO.
		$cv->workExperience()->sync($requestCv->work_experience);
	  }

	  // same thing as WorkExp
	  if ($this->request->has('cv.education'))
	  {
		foreach ($requestCv->education as $edu)
		{
		  $clonedRequest = (clone $this->request)->replace($edu);
		  $clonedRequest->validate((new EducationController($clonedRequest))->rules());
		}

		$cv->education()->sync($requestCv->education);
	  }

	  // Same as previous 2
	  if ($this->request->has('cv.certifications'))
	  {
		$relatedKeyName = CvCertification::getKeyName();
		foreach ($requestCv->certifications as $cert)
		{
		  $existing      = isset($cert[$relatedKeyName]) && !empty($cert[$relatedKeyName]);
		  $clonedRequest = (clone $this->request)->replace($cert);
		  $clonedRequest
			->validate(
			  (new CertificationsController($clonedRequest))
				->rules(!$existing)
			);
		}

		// separate loop because we don't want to create files if
		// the validation in the previous loop fails at any point
		foreach ($requestCv->certifications as $idx => $cert)
		{
		  $existing = isset($cert[$relatedKeyName]) && !empty($cert[$relatedKeyName]);
		  $path     = $this->request->file($cert->_request_file)->store('certifications');

		  if ($path)
		  {
			if ($existing)
			  Storage::delete($cert->file);
			$requestCv->certifications[$idx]->file = $path;
		  }
		  else
			throw new \Exception('Problem saving file.');
		}

		$cv->certifications()->sync($requestCv->certifications);
	  }
	} catch (\Throwable $e)
	{
	  // Roll back tx
	  rescue(function () {
		DB::rollBack();
	  });

	  report($e);
	  return response()->json([
		'success' => false,
		'message' => 'Could not complete your request.',
	  ], 500);
	}

	// Make changes
	DB::commit();
	return response()->json([
	  'success' => true,
	], 200);
  }
}