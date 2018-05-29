<?php

namespace App\Http\Controllers;

use App\Advert;
use App\Enum\AdvertStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class DashController extends Controller
{
    protected static $perPage = 8;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    private function employer_dash(Request $request)
    {
        $user = Auth::user()->load(['company', 'company.applications']);
        $currentPage = $request->get('page', 1);

        $applicationsCount =
            $user
                ->company
                ->applications()
                ->count();
        $applications =
            $user
                ->company
                ->applications()
                ->orderByDesc('created_at')
                ->skip(self::$perPage * ($currentPage - 1))
                ->take(self::$perPage)
                ->get()
                ->map(function ($item, $key) {
                    $item['_feed_type'] = 'application';
                    return $item;
                });

        $feed        = collect($applications);
        $paginator   = new LengthAwarePaginator(
            // items
            array_slice($feed->all(), 0, self::$perPage, true),
            // total
            $applicationsCount,
            self::$perPage,
            $currentPage,
            [ 'path' => $request->path() ]
        );

        return $paginator;
    }

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    private function employee_dash(Request $request)
    {
        $user = Auth::user()->load(['cv', 'cv.preferences']);
        $currentPage = $request->get('page', 1);

        $applicationsCount =
            $user
                ->applications()
                ->count();
        $applications =
            $user
                ->applications()
                ->orderByDesc('updated_at')
                ->skip(self::$perPage * ($currentPage - 1))
                ->take(self::$perPage)
                ->get()
                ->map(function ($item, $key) {
                    $item['_feed_type'] = 'application';
                    return $item;
                });

        $adverts =
            Advert
                ::whereStatus(AdvertStatus::Public)
                ->with(['jobRole', 'company', 'address']);

        if(isset(optional($user)->cv->preferences->job_role))
            $adverts->whereJobRole($user->cv->preferences->job_role);

        if(isset(optional($user)->cv->preferences->setting))
            $adverts->whereSetting($user->cv->preferences->setting);

        if(isset(optional($user)->cv->preferences->type))
            $adverts->whereType($user->cv->preferences->type);

        $advertsCount = $adverts->count();
        $adverts =
            $adverts
                ->inRandomOrder()
                ->skip(self::$perPage * ($currentPage - 1))
                ->take(self::$perPage)
                ->get()
                ->map(function ($item, $key) {
                    $item['_feed_type'] = 'advert';
                    return $item;
                });

        $applications = collect($applications);
        $adverts      = collect($adverts);
        $feed         = collect([$applications->shift()]);

        $loadedItemsCount = $applications->count() + $adverts->count();
        for ($i = 0; $i < $loadedItemsCount; $i++)
        {
            if(!($i % random_int(3, 4))) $feed->push($adverts->shift());
            else $feed->push($applications->shift());
        }

        $paginator = new LengthAwarePaginator(
            // items
            array_slice($feed->all(), 0, self::$perPage,true),
            // total
            $applicationsCount + $advertsCount,
            self::$perPage,
            $currentPage,
            [ 'path' => $request->path() ]
        );

        $advert_ids = array_map(
            function($item) {
                return $item->id;
            },
            array_filter($paginator->items(),
                function($item) {
                    return $item['_feed_type'] === 'advert';
                }
            )
        );

        if(count($advert_ids) > 0)
            Advert::whereIn('id', $advert_ids)->increment('recommended_impressions');

        return $paginator;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if($user->isCompany())
            return view('company.dashboard', ['items' => $this->employer_dash($request)]);
        else
            return view('employee.dashboard', ['items' => $this->employee_dash($request)]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     * @throws \Throwable
     */
    public function get(Request $request)
    {
        $user = Auth::user();

        if($user->isCompany())
            $paginator = $this->employer_dash($request);
        else
            $paginator = $this->employee_dash($request);

        return view('employee._dash-collection', ['items' => $paginator->items()])->render();
    }
}
