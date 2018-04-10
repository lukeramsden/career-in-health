<?php

namespace App\Http\Controllers;

use App\Advert;
use App\AdvertApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvertApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('create');
        $this->middleware('only.employee')->except('update');
    }

    private function getValidateRules(bool $internal)
    {
        return $internal ? [
                    'status' => 'nullable|integer',
                    'notes' => 'nullable|string|max:500'
                ] : [
                    'custom_cover_letter' => 'nullable|string|max:3000',
                ];
    }

    public function index()
    {
        return view('employee.view-applications')
            ->with(['applications' => Auth::user()->applications()->with('user', 'advert', 'advert.company', 'advert.jobType')->get()]);
    }

    public function create(Advert $advert)
    {
        if(Auth::guest()) {
            session(['apply_to_job_id' => $advert->id]);
            return redirect(route('register'));
        }

        if(AdvertApplication::alreadyApplied(Auth::user(), $advert))
        {
            return redirect(route('advert.show', [$advert]))
                        ->with([
                            'status' => 'You have already applied to this job!'
                        ]);
        }

        return view('employee.advert.apply')
            ->with(['advert' => $advert]);
    }

    public function store(Request $request, Advert $advert)
    {
        if(AdvertApplication::alreadyApplied(Auth::user(), $advert))
        {
            return redirect(route('advert.show', [advert]))
                        ->with([
                            'status' => 'You have already applied to this job!'
                        ]);
        }
        
        $data = $request->validate($this->getValidateRules(false));

        $application = new AdvertApplication();
        $application->user_id = Auth::user()->id;
        $application->fill($data);
        $advert->applications()->save($application);

        return redirect(route('advert.show', [$advert]))
            ->with([
                'status' => 'Applied!'
            ]);
    }

    public function update(Request $request, AdvertApplication $application)
    {
        if ($request->has('status') || $request->has('notes')) {
            $user = Auth::user();
            if(!$user->isCompany() || $application->advert->company_id !== $user->company_id) {
                return $request->ajax() ?
                    response()->json(['success' => false, 'message' => 'You must own the advert to update an application\'s status'])
                    : back()->with(['status' => 'You must own the advert to update an application\'s status']);
            } else {
                $data = $request->validate($this->getValidateRules(true));

                if($request->has('status'))
                    $application->status = $data['status'];

                if($request->has('notes'))
                    $application->notes = $data['notes'];
            }
        } else {
            $data = $request->validate($this->getValidateRules(false));

            $application->fill($data);
        }

        $application->save();

        return $request->ajax() ? response()->json(['success' => true])
            : back()->with(['status' => 'Success!']);
    }
}
