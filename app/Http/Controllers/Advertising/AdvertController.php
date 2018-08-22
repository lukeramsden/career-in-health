<?php

namespace App\Http\Controllers\Advertising;

use App\Advertising\Advert;
use App\Advertising\Advertiser;
use App\Advertising\HomePageAdvert;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
	protected function rules($custom = [], $update = false)
	{
		$data = $this->request->validate([
			'advert_type' => [
				'required', 'integer',
				Rule::in([
					Advert::TYPE_HOMEPAGE,
				]),
			],
		]);

		$advertType = $data['advert_type'];

		$rules = [
			'active' => 'boolean',
		];

		switch ($advertType)
		{
			case Advert::TYPE_HOMEPAGE:
				$rules = array_merge($rules, [
					'image'    => ($update ? 'nullable' : 'required') . '|image|max:2048|mimes:jpg,jpeg,png|dimensions:min_height=200,ratio=4/1',
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
	 * @throws \Throwable
	 */
	public function store()
	{
		$this->authorize('create', Advert::class);

		$data = $this->request->validate(self::rules([]));

		switch ($this->request->has('advert_type') ? $this->request->advert_type : null)
		{
			case Advert::TYPE_HOMEPAGE:
				$advertable = new HomePageAdvert([
					'links_to'   =>
						$data['links_to'],
					'image_path' =>
						$this->request->file('image')->store('home_page_advert_images'),
				]);
				break;
			default:
				throw new BadRequestHttpException('invalid advert_type');
		}

		$advert                = new Advert();
		$advert->active        = $data['active'] ?? false;
		$advert->advertiser_id = Auth::user()->userable_id;

		DB::transaction(function () use ($advert, $advertable)
		{
			$advertable->save();
			$advert->advertable()->associate($advertable);
			$advert->save();
		});

		if (ajax())
			return response()->json(['success' => true, 'model' => $advert], 200);

		return redirect(route('advertising.edit', [$advert]));
	}

	/**
	 * @param Advert $advert
	 *
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 * @throws \Throwable
	 */
	public function update(Advert $advert)
	{
		$this->authorize('edit', $advert);

		$data = $this->request->validate(self::rules([], true));

		switch ($this->request->has('advert_type') ? $this->request->advert_type : null)
		{
			case Advert::TYPE_HOMEPAGE:
				/** @var HomePageAdvert $advertable */
				$advertable = $advert->advertable;

				$advertable->links_to = $data['links_to'];

				if ($this->request->hasFile('image'))
				{
					$old_path = $advertable->image_path;

					$advertable->image_path =
						$this->request->file('image')->store('home_page_advert_images');
					Storage::delete($old_path);
				}

				$advert->active = $data['active'] ?? false;
				DB::transaction(function () use ($advert, $advertable)
				{
					$advertable->save();
					$advert->save();
				});
				break;
			default:
				throw new BadRequestHttpException('invalid advert_type');
		}

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
