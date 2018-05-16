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
            'name' => 'required|max:100',
            'address_line_1' => 'max:60',
            'address_line_2' => 'max:60',
            'address_line_3' => 'max:60',
            'town' => 'required',
            'county' => 'max:40',
            'postcode' => 'max:10'
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

        return redirect('/home');
    }

}
