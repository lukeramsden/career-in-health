<?php

namespace App\Http\Controllers\Advertiser;

use App\Advert;
use App\Advertiser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdvertController extends Controller
{
	protected $request;

	/**
	 * AdvertController constructor.
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;

		$this->middleware('auth');
	}

	/**
	 * @param array $custom
	 *
	 * @return array
	 */
	protected function rules($custom = [])
	{
		$rules = [
			'savingForLater'    => 'present|boolean',
			// core
			'title'             => 'required|string',
			'body'              => 'nullable|string',
			'image'             => 'nullable|image|max:3072|mimes:jpg,jpeg,png',
			'removeImage'       => 'nullable|boolean',
			'location_id'       => 'nullable|integer|exists:locations,id',
			// demographics
			'dem_location_id'   => 'nullable|integer|exists:locations,id',
			'dem_location_any'  => 'nullable|boolean',
			'dem_job_role_id'   => 'nullable|integer|exists:job_roles,id',
			'dem_job_role_any'  => 'nullable|boolean',
			'dem_will_relocate' => 'nullable|boolean',
		];

		if ($this->request->has('savingForLater')
			&& $this->request->savingForLater == true)
			$rules = array_merge($rules, [
				'links_to' => 'nullable|string|max:500',
			]);
		else
			$rules = array_merge($rules, [
				'links_to' => 'required|string|max:500',
			]);

		return array_merge($rules, $custom);
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function create()
	{
		$this->authorize('create', Advert::class);

		return view('advertising.create')
			->with([
				'advert' => new Advert(),
				'edit'   => false,
			]);
	}

	/**
	 * @param Advert $advert
	 *
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function edit(Advert $advert)
	{
		$this->authorize('edit', $advert);

		return view('advertising.create')
			->with([
				'advert' => $advert,
				'edit'   => true,
			]);
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function index()
	{
		$this->authorize('create', Advert::class);

		/** @var Advertiser $advertiser */
		$advertiser = Auth::user()->userable;
		return view('advertising.index')
			->with([
				'adverts' => $advertiser->adverts,
			]);
	}

	/**
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function store()
	{
		$this->authorize('create', Advert::class);

		$data = $this->request->validate(self::rules([]));

		$advert                = new Advert($data);
		$advert->active        = $data['active'] ?? false;
		$advert->advertiser_id = Auth::user()->userable_id;

		if ($this->request->hasFile('image'))
		{
			$path               = $this->request->file('image')->storePublicly('advert_images');
			$advert->image_path = $path;
		}

		$advert->save();

		if (ajax())
			return response()->json(['success' => true, 'model' => $advert], 200);

		return redirect(route('advertising.edit', [$advert]));
	}

	/**
	 * @param Advert $advert
	 *
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function update(Advert $advert)
	{
		$this->authorize('edit', $advert);

		$data = $this->request->validate(self::rules([]));

		if ($this->request->has('removeImage') && $data['removeImage'])
		{
			Storage::delete($advert->image_path);
			$advert->image_path = null;
		}
		elseif ($this->request->hasFile('image'))
		{
			$path = $this->request->file('image')->storePublicly('advert_images');
			Storage::delete($advert->image_path);
			$advert->image_path = $path;
		}

		$advert->fill($data);

		if($this->request->has('dem_location_id'))
		{
			$advert->dem_location_id = $data['dem_location_id'] ?? null;
			$advert->dem_location_any = $data['dem_location_id'] == null ? true : false;
		}

		if($this->request->has('dem_job_role_id'))
		{
			$advert->dem_job_role_id = $data['dem_job_role_id'] ?? null;
			$advert->dem_job_role_any = $data['dem_job_role_id'] == null ? true : false;
		}

		$advert->save();

		if (ajax())
			return response()->json(['success' => true, 'model' => $advert], 200);

		return redirect(route('advertising.edit', [$advert]));
	}

	/**
	 * @param Advert $advert
	 *
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 * @throws \Exception
	 */
	public function destroy(Advert $advert)
	{
		$this->authorize('edit', $advert);

		$advert->delete();

		if (ajax())
			return response()->json(['success' => true], 200);

		return redirect(route('advertising.index'));

	}
}
