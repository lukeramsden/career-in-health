<?php

namespace App\Http\Controllers;

use Auth;
use App\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('only.employer');
    }

    private function getValidationRules($request)
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

    public function create()
    {
        return view('address.create')
            ->with([
                'address' => new Address(),
                'edit' => false
            ]);
    }

    public function edit(Address $address)
    {
        return view('address.create')
            ->with([
                'address' => $address,
                'edit' => true
            ]);
    }

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

}
