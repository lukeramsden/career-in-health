<?php

namespace App\Http\Controllers\Cv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

  public function store()
  {
	$this->request->validate([
	  'cv' => 'required|object',
	]);

	
  }
}