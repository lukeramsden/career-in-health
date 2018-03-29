<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('only.employer');
    }

    public function create()
    {
        return view('address.create')
            ->with([
                'address' => new Address()
            ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'address_line_1' => 'max:60',
            'address_line_2' => 'max:60',
            'address_line_3' => 'max:60',
            'town' => 'required',
            'county' => 'max:40',
            'postcode' => 'max:10'
        ]);

        $address = new Address();
        $address->company_id = Auth::user()->company_id;
        $address->fill($request->all());
        $address->save();

        return redirect('/home');
    }

}
