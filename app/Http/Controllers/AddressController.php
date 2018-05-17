<?php

namespace App\Http\Controllers;

use Auth;
use App\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * AddressController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('only.employer');
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getValidationRules(Request $request)
    {
        return [
            'name' => 'required|max:120',
            'location_id' => 'required|integer|exists:locations,id',
            'address_line_1' => 'required|max:60',
            'address_line_2' => 'nullable|max:60',
            'address_line_3' => 'nullable|max:60',
            'county' => 'required|max:40',
            'postcode' => 'required|max:10|postcode',
        ];
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $addresses = $user->company->addresses()->get();
        return view('address.index')
            ->with([
                'addresses' => $addresses
            ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('address.create')
            ->with([
                'address' => new Address(),
                'edit' => false
            ]);
    }

    /**
     * @param Address $address
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Address $address)
    {
        return view('address.create')
            ->with([
                'address' => $address,
                'edit' => true
            ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $data = $request->validate(self::getValidationRules($request));

        $address = new Address();
        $address->company_id = Auth::user()->company_id;
        $address->fill($data);
        $address->save();

        if(ajax())
            return response()->json(['success' => true, 'model' => $address], 200);

        toast()->success('Created!');
        return redirect(route('address.edit', ['address' => $address]));
    }

    /**
     * @param Address $address
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(Address $address, Request $request)
    {
        $data = $request->validate(self::getValidationRules($request));

        $address->fill($data);
        $address->save();

        if(ajax())
            return response()->json(['success' => true, 'model' => $address], 200);

        toast()->success('Updated!');
        return back();
    }

    /**
     * @param Address $address
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Address $address)
    {
        if($address->adverts()->count() > 0)
        {
            if(ajax())
                return response()->json(['success' => false], 409);

            toast()->error('There are still adverts for this address, please remove them first.');
            return back();
        }

        $address->delete();

        if(ajax())
            return response()->json(['success' => true], 200);

        toast()->success('Deleted');
        return redirect(route('address.index'));
    }

}
