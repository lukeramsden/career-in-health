<?php

namespace App\Http\Controllers\Advertising;

use App\Advertising\Advert;
use App\Advertising\Advertiser;
use App\Advertising\HomePageAdvert;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
		$advertType = ($this->request->validate([
			'advert_type' => [
				'required|integer',
				Rule::in([
					Advert::TYPE_HOMEPAGE,
				]),
			],
		]))['advert_type'];

		$rules = [
			'active' => 'boolean',
		];

		switch ($advertType)
		{
			case Advert::TYPE_HOMEPAGE:
				$rules = array_merge($rules, [
					'image'    => 'required|image|size:|max:2048|mimes:jpg,jpeg,png|min_height:270,ratio:24/9',
					'links_to' => 'nullable|string|max:500',
				]);
				break;
			default:
				throw new BadRequestHttpException('invalid advert_type');
		}

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

		switch ($this->request->has('advert_type') ? $this->request->advert_type : null)
		{
			case Advert::TYPE_HOMEPAGE:
				($advertable = new HomePageAdvert([
					'links_to'   =>
						$data['links_to'],
					'image_path' =>
						$this->request->file('image')->store('home_page_advert_images'),
				]))->save();
				break;
			default:
				throw new BadRequestHttpException('invalid advert_type');
		}

		$advert                = new Advert($data);
		$advert->active        = $data['active'] ?? false;
		$advert->advertiser_id = Auth::user()->userable_id;
		$advert->advertable()->associate($advertable);
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
		abort(500);
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
