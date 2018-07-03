<?php

namespace App\Http\Controllers;

use Auth;
use App\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->middleware('auth');
        $this->middleware('user-type:company');
        $this->middleware('company-created');
    }

    protected function rules()
    {
        return [
            'name'           => 'required|max:120',
            'location_id'    => 'required|integer|exists:locations,id',
            'address_line_1' => 'required|max:60',
            'address_line_2' => 'nullable|max:60',
            'address_line_3' => 'nullable|max:60',
            'county'         => 'required|max:40',
            'postcode'       => 'required|max:10|postcode',
        ];
    }

    public function index()
    {
        $user = Auth::user();
        $addresses = $user->company->addresses()->get();
        return view('address.index')
            ->with([
                'addresses' => $addresses
            ]);
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

    public function store()
    {
        $data = $this->request->validate(self::rules());

        $address = new Address();
        $address->company_id = Auth::user()->userable->company->id;
        $data['postcode'] = strtoupper($data['postcode']);
        $address->fill($data);
        $address->save();

        if(Auth::user()->onboarding()->inProgress())
            return redirect(Auth::user()->onboarding()->nextUnfinishedStep()->link);

        if(ajax())
            return response()->json(['success' => true, 'model' => $address], 200);

        toast()->success('Created!');
        return redirect(route('address.edit', ['address' => $address]));
    }

    public function update(Address $address)
    {
        $data = $this->request->validate(self::rules());

        $address->fill($data);
        $address->save();

        if(ajax())
            return response()->json(['success' => true, 'model' => $address], 200);

        toast()->success('Updated!');
        return back();
    }

    /**
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
