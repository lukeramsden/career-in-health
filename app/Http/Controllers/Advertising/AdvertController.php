<?php

namespace App\Http\Controllers\Advertising;

use App\Advertising\Advert;
use App\Advertising\Advertiser;
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
		$rules = [];

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
		$advert->save();

		//

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
		$advert->fill($data);
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
