<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;

class MediaController extends Controller
{
	protected $request;

	/**
	 * MediaController constructor.
	 *
	 * @param Request $request
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;

		$this->middleware('auth');
	}

	/**
	 * @param Media $media
	 *
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 * @throws \Exception
	 */
	public function destroy(Media $media)
	{
		$media->delete();

		if (ajax())
			return response()->json(['success' => true], 200);

		toast()->success('Deleted');
		return back();
	}
}
