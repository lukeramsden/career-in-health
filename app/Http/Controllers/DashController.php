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
    protected static $perPage = 10;

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
        $user = Auth::user();

        $user->load(['company', 'company.applications']);

        // same issue as employee dashboard
        $applications = $user
            ->company
            ->applications()
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($item, $key) {
                $item['_feed_type'] = 'application';
                return $item;
            });

        $feed = collect($applications);
        $currentPage = $request->get('page', 1);
        $items = array_slice($feed->all(), ($currentPage * self::$perPage) - self::$perPage, self::$perPage, true);
        $paginator = new LengthAwarePaginator($items, $feed->count(), self::$perPage, $currentPage, [
                'path' => $request->path()
            ]);

        return $paginator;    }

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    private function employee_dash(Request $request)
    {
        /**
         * Note:
         *
         * Better way would be to find 10 random adverts that match job preferences,
         * and then scatter them throughout the data.
         *
         * Currently we're just loading every advert in to memory as an array, then splicing it.
         * This isn't a good idea because if we have gigabytes of adverts everything will break
         *
         * TODO: find out a way of doing this without loading every fucking item in to memory
         **/

        $user = Auth::user()->load(['cv', 'cv.preferences']);

        $applications = $user
            ->applications()
            ->get()
            ->map(function ($item, $key) {
                $item['_feed_type'] = 'application';
                return $item;
            });

        $adverts = Advert
            ::where('status', AdvertStatus::Public)
            ->with(['applications', 'jobRole', 'company', 'address']);

        if(isset(optional($user)->cv->preferences->job_role))
            $adverts->where('job_role', $user->cv->preferences->job_role);

        if(isset(optional($user)->cv->preferences->setting))
            $adverts->where('setting', $user->cv->preferences->setting);

        if(isset(optional($user)->cv->preferences->type))
            $adverts->where('type', $user->cv->preferences->type);

        $adverts = $adverts
            ->get()
            ->map(function ($item, $key) {
                $item['_feed_type'] = 'advert';
                return $item;
            });

        $feed = collect($applications->sortByDesc('last_edited'))->merge(collect($adverts));
        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $items = array_slice($feed->all(), ($currentPage * $perPage) - $perPage, $perPage, true);
        $paginator = new LengthAwarePaginator($items, $feed->count(), $perPage, $currentPage, [
                'path' => $request->path()
            ]);

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
