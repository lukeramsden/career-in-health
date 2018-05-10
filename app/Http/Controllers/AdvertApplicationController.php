<?php

namespace App\Http\Controllers;

use App\Advert;
use App\AdvertApplication;
use Carbon\Carbon;
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
            ->with(['applications' => Auth::user()->applications()->with('user', 'advert', 'advert.company', 'advert.jobRole')->orderBy('created_at', 'desc')->paginate(15)]);
    }

    public function show(AdvertApplication $application)
    {
        return view('employee.advert.view-application')
            ->with(['application' => $application]);
    }

    public function create(Advert $advert)
    {
        if(Auth::guest()) {
            session(['apply_to_job_id' => $advert->id]);
            return redirect(route('register'));
        }

        if(AdvertApplication::alreadyApplied(Auth::user(), $advert))
        {
            toast()->error('You have already applied to this job!');
            return redirect(route('advert.show', [$advert]));
        }

        return view('employee.advert.apply')
            ->with(['advert' => $advert]);
    }

    public function store(Request $request, Advert $advert)
    {
        if(AdvertApplication::alreadyApplied(Auth::user(), $advert))
        {
            toast()->error('You have already applied to this job!');
            return redirect(route('advert.show', [$advert]));
        }
        
        $data = $request->validate($this->getValidateRules(false));

        $application = new AdvertApplication();
        $application->user_id = Auth::user()->id;
        $application->fill($data);
        $application->last_edited = Carbon::now();;
        $advert->applications()->save($application);


        toast()->success('Applied!');
        return redirect(route('advert.show', [$advert]));
    }

    public function update(Request $request, AdvertApplication $application)
    {
        $user = Auth::user();
        if ($request->has('status') || $request->has('notes')) {
            if(!$user->isCompany() || $application->advert->company !== $user->company) {
                if($request->ajax())
                {
                    return response()->json(['success' => false, 'message' => 'You must own the advert to update an application\'s status or notes.'], 401);
                }

                toast()->error('You must own the advert to update an application\'s status');
                return back();
            } else {
                $data = $request->validate($this->getValidateRules(true));

                if(isset($data['status']))
                    $application->status = $data['status'];

                if(isset($data['notes']))
                    $application->notes = $data['notes'];
            }
        } else {
            $data = $request->validate($this->getValidateRules(false));

            $application->fill($data);
        }

        $application->last_edited = Carbon::now();;
        $application->save();

        if($request->ajax())
        {
            return response()->json(['success' => true]);
        }

        toast()->success('Updated!');
        return back();
    }
}
