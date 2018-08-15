<?php

namespace App\Http\Controllers\Advertiser;

use App\Advert;
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
		if ($this->request->has('savingForLater')
			&& $this->request->savingForLater == true)
		{
			return array_merge([
				'savingForLater' => 'nullable|boolean',
				'title'          => 'required|string',
				'body'           => 'nullable|string',
				'image'          => 'nullable|image|max:3072|mimes:jpg,jpeg,png',
				'removeImage'    => 'nullable|boolean',
				'links_to'       => 'nullable|string|max:500',
			], $custom);
		}
		else
		{
			return array_merge([
				'savingForLater' => 'nullable|boolean',
				'title'          => 'required|string',
				'body'           => 'nullable|string',
				'image'          => 'nullable|image|max:3072|mimes:jpg,jpeg,png',
				'removeImage'    => 'nullable|boolean',
				'links_to'       => 'required|string|max:500',
			], $custom);
		}
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
		$advert->save();

		if (ajax())
			return response()->json(['success' => true, 'model' => $advert], 200);

		return redirect(route('advertising.edit', [$advert]));
	}
}
