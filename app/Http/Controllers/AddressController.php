<?php

namespace App\Http\Controllers;

use App\Address;
use Auth;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded;
use Webpatser\Uuid\Uuid;

class AddressController extends Controller
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;

		$this->middleware('auth')->except('show');
		$this->middleware('user-type:company')->except('show');
		$this->middleware('company-created')->except('show');
	}

	public function index()
	{
		return view('address.index')
			->with([
				'addresses' => Auth::user()->userable->company->addresses,
			]);
	}

	public function show(Address $address)
	{
		return view('address.show')
			->with([
				'address' => $address,
			]);
	}

	public function create()
	{
		return view('address.create')
			->with([
				'address' => new Address(),
				'edit'    => false,
			]);
	}

	public function edit(Address $address)
	{
		return view('address.create')
			->with([
				'address' => $address,
				'edit'    => true,
			]);
	}

	public function store()
	{
		$data = $this->request->validate(self::rules());

		$address             = new Address();
		$address->company_id = Auth::user()->userable->company->id;
		$data['postcode']    = str_replace(' ', '', strtoupper($data['postcode']));
		$address->fill($data);
		$address->save();

		foreach ($this->request->file('images', []) as $image)
		{
			try
			{
				$address
					->addMedia($image)
					->usingFileName(Uuid::generate(4)->string)
					->toMediaCollection('images');
			} catch (FileCannotBeAdded $e)
			{
				toast()->error("{$image->getClientOriginalName()} failed to upload, please try again.");
			} catch (\Exception $e)
			{
				toast()->error("Unknown error with file {$image->getClientOriginalName()}");
			}
		}

		if (Auth::user()->onboarding()->inProgress())
			return redirect(Auth::user()->onboarding()->nextUnfinishedStep()->link);

		if (ajax())
			return response()->json(['success' => true, 'model' => $address], 200);

		toast()->success('Created!');
		return redirect(route('address.edit', ['address' => $address]));
	}

	protected function rules($custom = [])
	{
		return array_merge([
			'name'           => 'required|max:120',
			'location_id'    => 'required|integer|exists:locations,id',
			'address_line_1' => 'required|max:60',
			'address_line_2' => 'nullable|max:60',
			'address_line_3' => 'nullable|max:60',
			'county'         => 'required|max:40',
			'postcode'       => 'required|max:10|postcode',
			'images'         => 'nullable|array',
			'images.*'       => 'image|max:10240|mimes:jpg,jpeg,png',
		], $custom);
	}

	public function update(Address $address)
	{
		$data = $this->request->validate(self::rules());

		$address->fill($data);
		$address->save();

		if (ajax())
			return response()->json(['success' => true, 'model' => $address], 200);

		toast()->success('Updated!');
		return back();
	}

	/**
	 * @throws \Exception
	 */
	public function destroy(Address $address)
	{
		if ($address->adverts()->count() > 0)
		{
			if (ajax())
				return response()->json(['success' => false], 409);

			toast()->error('There are still adverts for this address, please remove them first.');
			return back();
		}

		$address->delete();

		if (ajax())
			return response()->json(['success' => true], 200);

		toast()->success('Deleted');
		return redirect(route('address.index'));
	}

}
