<?php

namespace App\Http\Controllers;

use App\Advert;
use App\AdvertStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class DashController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function employer_dash(Request $request)
    {
        $user = Auth::user();

        $user->load(['company', 'company.applications']);

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
        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $items = array_slice($feed->all(), ($currentPage * $perPage) - $perPage, $perPage, true);
        $paginator = new LengthAwarePaginator($items, $feed->count(), $perPage, $currentPage, [
                'path' => $request->path()
            ]);

        return $paginator;    }

    private function employee_dash(Request $request)
    {
        $user = Auth::user();

        $user->load(['cv', 'cv.preferences']);

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

        $adverts = $adverts->get()
            ->map(function ($item, $key) {
                $item['_feed_type'] = 'advert';
                return $item;
            });

        $feed = collect($applications)->merge(collect($adverts));
        $feed = $feed->sortByDesc('last_edited');
        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $items = array_slice($feed->all(), ($currentPage * $perPage) - $perPage, $perPage, true);
        $paginator = new LengthAwarePaginator($items, $feed->count(), $perPage, $currentPage, [
                'path' => $request->path()
            ]);

        foreach ($paginator->items() as $item)
            if($item['_feed_type'] === 'advert')
                $item->increment('recommended_impressions');

        return $paginator;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        if($user->isCompany())
        {
            $paginator = $this->employer_dash($request);
            return view('company.dashboard', ['items' => $paginator]);
        } else {
            $paginator = $this->employee_dash($request);
            return view('employee.dashboard', ['items' => $paginator]);
        }
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
        {
            $paginator = $this->employer_dash($request);
        } else {
            $paginator = $this->employee_dash($request);
        }

        return view('employee._dash-collection', ['items' => $paginator->items()])->render();
    }
}
