<?php

namespace App\Http\Controllers;

use Auth;
use App\Advert;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Closure;

// TODO: Hide advert on save_for_later - middleware for this?
class AdvertController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show');
        $this->middleware('only.employer')->except('show');
        $this->middleware(function($request, Closure $next) {
            // TODO: better validation for advert belonging to company creating advert
            $advert = $request->route('advert');
            if(isset($advert)) {
                if($advert->user != Auth::user()) {
                    redirect(route('advert.index'));
                }
            }

            return $next($request);
        })->except('show');
    }

    private function getValidateRules($request)
    {
        if ($request->has('save_for_later') && $request->save_for_later == true) {
            $rules = [
                'title' => 'required|max:120'
            ];
        } else {
            $rules = [
                'address_id' => 'required',
                'title' => 'required|max:120',
                'description' => 'required|max:3000',
                'job_type_id' => 'required|integer|exists:job_types,id',
                'setting' => ['required', Rule::in(array_keys(Advert::$settings))],
                'type' => ['required', Rule::in(array_keys(Advert::$types))],
                'min_salary' => 'nullable|integer|min:0|max:1000000|less_than_field:max_salary',
                'max_salary' => 'nullable|integer|min:1|max:1000000|greater_than_field:min_salary',
            ];
        }

        return $rules;
    }   

    public function index()
    {
        $user = Auth::user();
        $adverts = $user->company->adverts()->with('applications')->get();
        return view('advert.index')
            ->with([
                'adverts' => $adverts
            ]);
    }

    public function show(Advert $advert)
    {
        return view('advert.show')
            ->with([
                'advert' => $advert
            ]);
    }

    public function show_internal(Request $request, Advert $advert)
    {
        $user = Auth::user();
        if(!$user->isCompany()
            || $advert->company_id !== $user->company_id) {
            toast()->error('You must be the advert creator to view this page');
            return back();
        }

        return view('advert.show_internal')
            ->with([
                'advert' => $advert
            ]);
    }

    public function create()
    {
        return view('advert.create')
            ->with([
                'advert' => new Advert(),
                'edit' => false
            ]);
    }

    public function edit(Advert $advert)
    {
        return view('advert.create')
            ->with([
                'advert' => $advert,
                'edit' => true
            ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(self::getValidateRules($request));

        $advert = new Advert();
        $advert->company_id = Auth::user()->company_id;
        $advert->created_by_user_id = Auth::user()->id;
        $advert->fill($data);
        $advert->save();

        if(ajax())
        {
            return response()->json(['success' => true, 'model' => $advert], 200);
        }

        toast()->success('Created!');
        return redirect(route('advert.show', ['advert' => $advert]));
    }

    public function update(Advert $advert, Request $request)
    {
        $data = $request->validate(self::getValidateRules($request));

        $advert->fill($data);
        $advert->save();

        if(ajax())
        {
            return response()->json(['success' => true, 'model' => $advert], 200);
        }

        toast()->success('Updated!');
        return back();
    }
}
