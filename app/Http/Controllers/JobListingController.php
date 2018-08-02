<?php

namespace App\Http\Controllers;

use App\Address;
use Auth;
use App\JobListing;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Closure;

class JobListingController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->middleware('auth')->except('show');
        $this->middleware('user-type:company')->except('show');
        $this->middleware('company-created')->except('show');

        // editor ownership
        $this->middleware(function($request, Closure $next) {
            $user   = Auth::user();
            $jobListing = $request->route('job-listing');

            if(!isset($jobListing))
                return $next($request);

            if($user->isCompany() && $user->userable->company->id === $jobListing->company_id)
                return $next($request);

            if(ajax())
                return response()->json(['success' => false, 'message' => 'You cannot edit an job_listing you don\'t own'], 401);

            toast()->error('You cannot edit an job_listing you don\'t own');
            return redirect(route('job-listing.show', ['jobListing' => $jobListing]));
        })->only(['edit', 'update', 'showInternal', 'showApplications']);

        // address ownership
        $this->middleware(function($request, Closure $next) {
            $user = Auth::user();

            try {
                $address  = Address::findOrFail($request->get('address_id', -1));
            } catch (ModelNotFoundException $exception) {
                return $next($request);
            }

            if($user->isCompany() && $user->userable->company->id === $address->company_id)
                return $next($request);

            if(ajax())
                return response()->json(['success' => false, 'message' => 'You cannot use an address you don\'t own'], 401);

            toast()->error('You cannot use an address you don\'t own');
            return back()->withInput();
        })->only(['store', 'updated']);

        // public status
        $this->middleware(function($request, Closure $next) {
            $jobListing = $request->route('job-listing');

            if(isset($jobListing) && $jobListing->isDraft())
                return back();

            return $next($request);

        })->only('show');
    }

    protected function rules()
    {
        if ($this->request->has('savingForLater')
            && $this->request->savingForLater == true)
            return [
                'title'       => 'required|max:120',
                'address_id'  => 'nullable|integer|exists:addresses,id',
                'description' => 'nullable|max:3000',
                'job_role'    => 'nullable|integer|exists:job_roles,id',
                'setting'     => ['nullable', Rule::in(array_keys(JobListing::$settings))],
                'type'        => ['nullable', Rule::in(array_keys(JobListing::$types))],
                'min_salary'  => 'nullable|integer|min:0|max:1000000|less_than_field:max_salary',
                'max_salary'  => 'nullable|integer|min:1|max:1000000|greater_than_field:min_salary',
            ];

        return [
                'title'       => 'required|max:120',
                'address_id'  => 'required|integer|exists:addresses,id',
                'description' => 'required|max:3000',
                'job_role'    => 'required|integer|exists:job_roles,id',
                'setting'     => ['required', Rule::in(array_keys(JobListing::$settings))],
                'type'        => ['required', Rule::in(array_keys(JobListing::$types))],
                'min_salary'  => 'required|integer|min:0|max:1000000|less_than_field:max_salary',
                'max_salary'  => 'required|integer|min:1|max:1000000|greater_than_field:min_salary',
            ];
    }

    public function index()
    {
        return view('job-listing.index')
            ->with([
                'jobListings' =>
                    Auth
                        ::user()
                        ->userable
                        ->company
                        ->jobListings()
                        ->with('applications')
                        ->get()
            ]);
    }

    public function show(JobListing $jobListing)
    {
        session()->keep('clickThrough');
        $jobListing->increment('page_views');
        return view('job-listing.show')
            ->with([
                'jobListing' => $jobListing
            ]);
    }

    public function showInternal(JobListing $jobListing)
    {
        return view('job-listing.show-internal')
            ->with([
                'jobListing' => $jobListing
            ]);
    }

    public function showApplications(JobListing $jobListing)
    {
        return view('job-listing.view-applications')
            ->with([
                'jobListing' => $jobListing
            ]);
    }

    public function create()
    {
        return view('job-listing.create')
            ->with([
                'jobListing' => new JobListing(),
                'edit' => false
            ]);
    }

    public function edit(JobListing $jobListing)
    {
        return view('job-listing.create')
            ->with([
                'jobListing' => $jobListing,
                'edit' => true
            ]);
    }

    public function store()
    {
        $user = Auth::user();
        $userable = $user->userable;
        $company = $userable->company;
        $data = $this->request->validate(self::rules());

        $savingForLater = $this->request->has('savingForLater') && $this->request->savingForLater;

        $jobListing = new JobListing();
        $jobListing->fill($data);
        $jobListing->company_id = $company->id;
        $jobListing->created_by_user_id = $user->id;
        $jobListing->published = !$savingForLater;
        $jobListing->last_edited = Carbon::now();;
        $jobListing->save();

        if(!$company->has_created_first_job_listing)
        {
            $company->has_created_first_job_listing = true;
            $company->save();
        }

        if(ajax())
            return response()->json(['success' => true, 'model' => $jobListing], 200);

        if($savingForLater) {
            toast()
                ->success('Created!')
                ->info('This listing is not public yet.');
            return redirect(route('job-listing.edit', ['jobListing' => $jobListing]));
        }

        toast()
            ->success('Created!')
            ->info('This listing has been published.');
        return redirect(route('job-listing.show', ['jobListing' => $jobListing]));
    }

    public function update(JobListing $jobListing)
    {
        $data = $this->request->validate(self::rules());
        $savingForLater = $this->request->has('savingForLater') && $this->request->savingForLater == true;

        $jobListing->fill($data);
        $jobListing->published = !$savingForLater;
        $jobListing->last_edited = Carbon::now();
        $jobListing->save();

        if(ajax())
            return response()->json(['success' => true, 'model' => $jobListing], 200);

        toast()->success('Updated!');

        if($savingForLater)
            toast()->info('This listing is not public.');
        else
            toast()->info('This listing has been published successfully.<br><a href="' . route('job-listing.show', ['jobListing' => $jobListing]) . '" class="btn btn-action btn-sm mt-1">View JobListing</a>');

        return back();
    }

    /**
     * @param JobListing $jobListing
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(JobListing $jobListing)
    {
        $jobListing->delete();

        if(ajax())
            return response()->json(['success' => true], 200);

        toast()->success('Deleted');
        return redirect(route('job-listing.index'));
    }
}
