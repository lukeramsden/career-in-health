<?php

namespace App\Http\Controllers;

use App\Advert;
use App\AdvertApplication;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvertApplicationController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->middleware('auth')->except('create');
        $this->middleware('user-type:employee')->except('update');
        $this->middleware('mustOnboard');
    }

    protected function rules(bool $internal)
    {
        return $internal ? [
            'status' => 'nullable|integer',
            'notes'  => 'nullable|string|max:500'
        ] : [
            'custom_cover_letter' => 'nullable|string|max:3000',
        ];
    }

    public function index()
    {
        return view('employee.view-applications')
            ->with([
                'applications' =>
                    Auth
                        ::user()
                        ->userable
                        ->applications()
                        ->with('employee', 'advert', 'advert.company', 'advert.jobRole')
                        ->orderBy('created_at', 'desc')
                        ->paginate(15)
            ]);
    }

    public function show(AdvertApplication $application)
    {
        return view('employee.advert.view-application')
            ->with([
                'application' => $application
            ]);
    }

    public function create(Advert $advert)
    {
        if(Auth::guest()) {
            session(['jobApplyId' => $advert->id]);
            return redirect(route('register'));
        }

        if(AdvertApplication::hasApplied(Auth::user()->userable, $advert))
        {
            toast()->error('You have already applied to this job!');
            return redirect(route('advert.show', [$advert]));
        }

        session()->keep('clickThrough');

        return view('employee.advert.apply')
            ->with(['advert' => $advert]);
    }

    public function store(Advert $advert)
    {
        if(AdvertApplication::hasApplied(Auth::user()->userable, $advert))
        {
            toast()->error('You have already applied to this job!');
            return redirect(route('advert.show', [$advert]));
        }
        
        $data = $this->request->validate(self::rules(false));

        $application = new AdvertApplication();
        $application->fill($data);
        $application->employee_id = Auth::user()->userable->id;
        $application->last_edited = Carbon::now();;
        $advert->applications()->save($application);

        switch (session()->get('clickThrough', 'search')) {
            case 'search':
                $advert->increment('search_conversions');
                break;
            case 'recommended':
                $advert->increment('recommended_conversions');
                break;
        }

        toast()->success('Applied!');
        return redirect(route('advert.show', [$advert]));
    }

    public function update(AdvertApplication $application)
    {
        $user = Auth::user();
        // if editing status or notes
        if ($this->request->has('status') || $this->request->has('notes')) {
            // dont let non-owners edit status or notes for applications for adverts they dont own
            if(!$user->isValidCompany() || $application->advert->company->id !== $user->company->id) {
                if(ajax())
                    return response()->json(['success' => false, 'message' => 'You must own the advert to update an application\'s status or notes.'], 401);

                toast()->error('You must own the advert to update an application\'s status or notes.');
                return back();
            } else {
                $data = $this->request->validate(self::rules(true));

                if(isset($data['status']))
                    $application->status = $data['status'];

                if(isset($data['notes']))
                    $application->notes = $data['notes'];
            }
        } else
            $application->fill($this->request->validate(self::rules(false)));

        $application->last_edited = Carbon::now();
        $application->save();

        if(ajax())
            return response()->json(['success' => true]);

        toast()->success('Updated!');
        return back();
    }
}
