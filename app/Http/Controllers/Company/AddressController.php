<?php

namespace App\Http\Controllers;

use App\Address;
use Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded;
use Webpatser\Uuid\Uuid;

class AddressController extends Controller
{
  static    $maxMedia = 20;
  protected $request;

  /**
   * AddressController constructor.
   *
   * @param Request $request
   */
  public function __construct(Request $request)
  {
	$this->request = $request;

	$this->middleware('auth')->except('show');
	$this->middleware('user-type:company')->except('show');
	$this->middleware('company-created')->except('show');
  }

  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index()
  {
	return view('address.index')
	  ->with([
		'addresses' => Auth::user()->userable->company->addresses,
	  ]);
  }

  /**
   * @param Address $address
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function show(Address $address)
  {
	return view('address.show')
	  ->with([
		'address'     => $address,
		'jobListings' => $address
		  ->jobListings()
		  ->where('published', true)
		  ->orderBy('created_at', 'desc')
		  ->with('jobRole')
		  ->paginate(5),
	  ]);
  }

  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   * @throws \Illuminate\Auth\Access\AuthorizationException
   */
  public function create()
  {
	$this->authorize('create', Address::class);

	return view('address.create')
	  ->with([
		'address' => new Address(),
		'edit'    => false,
	  ]);
  }

  /**
   * @param Address $address
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   * @throws \Illuminate\Auth\Access\AuthorizationException
   */
  public function edit(Address $address)
  {
	$this->authorize('update', $address);

	return view('address.create')
	  ->with([
		'address' => $address,
		'edit'    => true,
	  ]);
  }

  /**
   * @param Address $address
   *
   * @return \Illuminate\Http\JsonResponse
   * @throws AuthorizationException
   */
  public function get(Address $address)
  {
//	try
//	{
//	  if (Auth::check())
//		$this->authorize('view', $address);
//	} catch (AuthorizationException $exception)
//	{
//	  return response()->json([
//		'success' => false,
//		'error'   => $exception,
//		'message' => $exception->getMessage(),
//	  ], 401);
//	}

	return response()->json([
	  'success' => true,
	  'model'   => $address,
	], 200);
  }

  /**
   * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   * @throws \Exception|\Illuminate\Auth\Access\AuthorizationException
   */
  public function store()
  {
	$this->authorize('create', Address::class);

	$data = $this->request->validate(self::rules([
	  'images'   => 'nullable|array|max:' . self::$maxMedia,
	  'images.*' => 'image|max:10240|mimes:jpg,jpeg,png',
	]));

	$address             = new Address();
	$address->company_id = Auth::user()->userable->company->id;
	$data['postcode']    = str_replace(' ', '', strtoupper($data['postcode']));
	$address->fill($data);
	$address->save();

	foreach ($this->request->file('images', []) as $image)
	{
	  if ($address->getMedia('images')->count() >= self::$maxMedia)
	  {
		toast()->error('Too many images, only the first ' . self::$maxMedia . ' have been added.');
		break;
	  }

	  try
	  {
		$address
		  ->addMedia($image)
		  ->usingFileName(Uuid::generate(4)->string . '.' . $image->clientExtension())
		  ->toMediaCollection('images');
	  } catch (FileCannotBeAdded $e)
	  {
		toast()->error("{$image->getClientOriginalName()} failed to upload, please try again.");
	  }
	}

	$redirectTo = route('address.edit', ['address' => $address]);

	if (Auth::user()->onboarding()->inProgress())
	  $redirectTo = Auth::user()->onboarding()->nextUnfinishedStep()->link;

	if (ajax())
	  return response()->json([
		'success'    => true,
		'model'      => $address,
		'redirectTo' => $redirectTo,
	  ], 200);

	toast()->success('Created!');
	return redirect($redirectTo);
  }

  /**
   * @param array $custom
   *
   * @return array
   */
  protected function rules($custom = [])
  {
	return array_merge([
	  'name'           => 'required|max:120',
	  'location_id'    => 'required|integer|exists:locations,id',
	  'address_line_1' => 'required|max:60',
	  'address_line_2' => 'nullable|max:60',
	  'address_line_3' => 'nullable|max:60',
	  'postcode'       => 'required|max:10|postcode',
	  'about'          => 'nullable|string|max:500',
	  'phone'          => 'nullable|string',
	  'email'          => 'nullable|string',
	], $custom);
  }

  /**
   * @param Address $address
   *
   * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
   * @throws \Illuminate\Auth\Access\AuthorizationException
   */
  public function update(Address $address)
  {
	$this->authorize('update', $address);

	$data = $this->request->validate(self::rules());

	$address->fill($data);
	$address->save();

	if (ajax())
	  return response()->json(['success' => true, 'model' => $address], 200);

	toast()->success('Updated!');
	return back();
  }

  /**
   * @throws \Exception|\Illuminate\Auth\Access\AuthorizationException
   */
  public function destroy(Address $address)
  {
	$this->authorize('delete', $address);

	if ($address->jobListings()->count() > 0)
	{
	  if (ajax())
		return response()->json([
		  'success' => false,
		  'message' => 'There are still listings for this address, please remove them first.',
		], 409);

	  toast()->error('There are still listings for this address, please remove them first.');
	  return back();
	}

	$address->delete();

	if (ajax())
	  return response()->json(['success' => true], 200);

	toast()->success('Deleted');
	return redirect(route('address.index'));
  }

  /**
   * @param Address $address
   *
   * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
   * @throws \Exception|\Illuminate\Auth\Access\AuthorizationException
   */
  public function addImage(Address $address)
  {
	$this->authorize('update', $address);

	if ($address->getMedia('images')->count() >= self::$maxMedia)
	{
	  if (ajax())
		return response()->json([
		  'success' => false,
		  'error'   => 'Too many images. Please remove an image to make space for a new one.',
		], 400);

	  toast()->error('Too many images. Please remove an image to make space for a new one.');
	  return back();
	}

	$data = $this->request->validate([
	  'image' => 'required|image|max:10240|mimes:jpg,jpeg,png',
	]);

	$image = $this->request->file('image');

	try
	{
	  $file = $address
		->addMedia($image)
		->usingFileName(Uuid::generate(4)->string . '.' . $image->clientExtension())
		->toMediaCollection('images');
	} catch (FileCannotBeAdded $e)
	{
	  $str = "{$image->getClientOriginalName()} failed to upload, please try again.";
	  if (ajax())
		return response()->json([
		  'success' => false,
		  'model'   => $address,
		  'media'   => $address->getMedia('images'),
		  'error'   => $str,
		], 500);

	  toast()->error($str);
	  return back();
	}

	if (ajax())
	  return response()->json([
		'success' => true,
		'model'   => $file,
		'media'   => $address->getMedia('images'),
	  ], 200);

	toast()->success('Added');
	return back();
  }

}
