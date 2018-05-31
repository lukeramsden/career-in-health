<?php

namespace App\Http\Controllers;

use App\Address;
use App\Enum\AdvertStatus;
use Auth;
use App\Advert;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Closure;

class AdvertController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->middleware('auth')->except('show');
        $this->middleware('only.employer')->except('show');

        // editor ownership
        $this->middleware(function($request, Closure $next) {
            $user   = Auth::user();
            $advert = $request->route('advert');

            if(!isset($advert))
                return $next($request);

            if($user->isCompany() && $user->company_id === $advert->company_id)
                return $next($request);

            if(ajax())
                return response()->json(['success' => false, 'message' => 'You cannot edit an advert you don\'t own'], 401);

            toast()->error('You cannot edit an advert you don\'t own');
            return redirect(route('advert.show', ['advert' => $advert]));
        })->only(['edit', 'update', 'showInternal', 'showApplications']);

        // address ownership
        $this->middleware(function($request, Closure $next) {
            $user = Auth::user();

            try {
                $address  = Address::findOrFail($request->get('address_id', -1));
            } catch (ModelNotFoundException $exception) {
                return $next($request);
            }

            if($user->isCompany() && $user->company_id === $address->company_id)
                return $next($request);

            if(ajax())
                return response()->json(['success' => false, 'message' => 'You cannot use an address you don\'t own'], 401);

            toast()->error('You cannot use an address you don\'t own');
            return back()->withInput();
        })->only(['store', 'updated']);

        // public status
        $this->middleware(function($request, Closure $next) {
            $advert = $request->route('advert');

            if(isset($advert) && $advert->status === AdvertStatus::Draft)
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
                'setting'     => ['nullable', Rule::in(array_keys(Advert::$settings))],
                'type'        => ['nullable', Rule::in(array_keys(Advert::$types))],
                'min_salary'  => 'nullable|integer|min:0|max:1000000|less_than_field:max_salary',
                'max_salary'  => 'nullable|integer|min:1|max:1000000|greater_than_field:min_salary',
            ];

        return [
                'title' => 'required|max:120',
                'address_id' => 'required|integer|exists:addresses,id',
                'description' => 'required|max:3000',
                'job_role' => 'required|integer|exists:job_roles,id',
                'setting' => ['required', Rule::in(array_keys(Advert::$settings))],
                'type' => ['required', Rule::in(array_keys(Advert::$types))],
                'min_salary' => 'required|integer|min:0|max:1000000|less_than_field:max_salary',
                'max_salary' => 'required|integer|min:1|max:1000000|greater_than_field:min_salary',
            ];
    }

    public function index()
    {
        return view('advert.index')
            ->with([
                'adverts' =>
                    Auth
                        ::user()
                        ->company
                        ->adverts()
                        ->with('applications')
                        ->get()
            ]);
    }

    public function show(Advert $advert)
    {
        session()->keep('clickThrough');
        $advert->increment('page_views');
        return view('advert.show')
            ->with([
                'advert' => $advert
            ]);
    }

    public function showInternal(Advert $advert)
    {
        return view('advert.show-internal')
            ->with([
                'advert' => $advert
            ]);
    }

    public function showApplications(Advert $advert)
    {
        return view('advert.view-applications')
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

    public function store()
    {
        $data = $this->request->validate(self::rules());

        $savingForLater = $this->request->has('savingForLater') && $this->request->savingForLater;

        $advert = new Advert();
        $advert->fill($data);
        $advert->company_id = Auth::user()->company_id;
        $advert->created_by_user_id = Auth::user()->id;
        $advert->status = $savingForLater ? AdvertStatus::Draft : AdvertStatus::Public;
        $advert->last_edited = Carbon::now();;
        $advert->save();

        if(ajax())
            return response()->json(['success' => true, 'model' => $advert], 200);

        if($savingForLater) {
            toast()
                ->success('Created!')
                ->info('This advert is not public yet.');
            return redirect(route('advert.edit', ['advert' => $advert]));
        }

        toast()
            ->success('Created!')
            ->info('This advert has been published.');
        return redirect(route('advert.show', ['advert' => $advert]));
    }

    public function update(Advert $advert)
    {
        $data = $this->request->validate(self::rules());
        $savingForLater = $this->request->has('savingForLater') && $this->request->savingForLater == true;

        $advert->fill($data);
        $advert->status = $savingForLater ? AdvertStatus::Draft : AdvertStatus::Public;
        $advert->last_edited = Carbon::now();
        $advert->save();

        if(ajax())
            return response()->json(['success' => true, 'model' => $advert], 200);

        toast()->success('Updated!');

        if($savingForLater)
            toast()->info('This advert is not public.');
        else
            toast()->info('This advert has been published successfully.<br><a href="' . route('advert.show', ['advert' => $advert]) . '" class="btn btn-action btn-sm mt-1">View Advert</a>');

        return back();
    }

    /**
     * @param Advert $advert
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Advert $advert)
    {
        $advert->delete();

        if(ajax())
            return response()->json(['success' => true], 200);

        toast()->success('Deleted');
        return redirect(route('advert.index'));
    }
}
