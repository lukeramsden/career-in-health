<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Advert;
use Illuminate\Http\Request;

class AdvertController extends Controller
{
    
    private function getValidateRules($request)
    {
        if ($request->save_for_later !== null) {
            $rules = [
                'title' => 'required'
            ];
        } else {
            $rules = [
                'title' => 'required|max:160',
                'description' => 'required|max:3000',
                'role' => 'required',
                'setting' => 'required',
                'type' => 'required',
            ];
        }

        return $rules;
    }   

    public function index()
    {
        return view('advert.index')
            ->with([
                'adverts' => Auth::user()->company->adverts
            ]);
    }

    public function create()
    {
        return view('advert.create')
            ->with([
                'advert' => new Advert()
            ]);
    }

    public function edit(Advert $advert)
    {
        return view('advert.create')
            ->with([
                'advert' => $advert
            ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->getValidateRules($request));

        $advert = new Advert();
        $advert->company_id = Auth::user()->company_id;
        $advert->created_by_user_id = Auth::user()->id;
        $advert->fill($request->all());
        $advert->save();

        return redirect(route('adverts'));
    }

    public function update(Advert $advert, Request $request)
    {
        $request->validate($this->getValidateRules($request));

        $advert->fill($request->all());
        $advert->save();

        return redirect(route('adverts'));
    }
}
