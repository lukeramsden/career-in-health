<?php

namespace App\Http\Controllers;

use App\Advert;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class DashController extends Controller
{
    protected $request;
    protected static $perPage = 8;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->middleware('auth');
    }

    protected function dashCompany()
    {
        $user = Auth::user()->load(['company', 'company.applications']);
        $currentPage = $this->request->get('page', 1);

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

        $feed = collect($applications);

        $paginator = new LengthAwarePaginator(
            // items
            array_slice($feed->all(), 0, self::$perPage, true),
            // total
            $applicationsCount,
            self::$perPage,
            $currentPage,
            [ 'path' => $this->request->path() ]
        );

        return $paginator;
    }

    protected function dashEmployee()
    {
        $user = Auth::user()->load(['cv', 'cv.preferences']);
        $currentPage = $this->request->get('page', 1);

        // Applications

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

        // Adverts

        $adverts =
            Advert
                ::wherePublished(true)
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

        // Private Message

//        $privateMessagesCount = $user->unreadMessages()->count();
//        $privateMessages =
//            $user
//                ->unreadMessages()
//                ->orderByDesc('created_at')
//                ->skip(self::$perPage * ($currentPage - 1))
//                ->take(self::$perPage)
//                ->get()
//                ->map(function ($item, $key) {
//                    $item['_feed_type'] = 'privateMessage';
//                    return $item;
//                });

        // collect all together

        $applications    = collect($applications);
        $adverts         = collect($adverts);
//        $privateMessages = collect($privateMessages);
        $feed            = collect([]);

        $count  = $applicationsCount;
        $count += $advertsCount;
//        $count += $privateMessagesCount;

        while($feed->count() < self::$perPage)
        {
            $item = null;
            switch(random_int(0, 1)) {
                case 0:
                    $item = $adverts->shift();
                    break;
                case 1:
                    $item = $applications->shift();
                    break;
//                case 2:
//                    $item = $privateMessages->shift();
//                    break;
            }
            if($item !== null)
                $feed->push($item);
        }

        $paginator = new LengthAwarePaginator(
            // items
            array_slice($feed->all(), 0, self::$perPage,true),
            // total
            $count,
            self::$perPage,
            $currentPage,
            [ 'path' => $this->request->path() ]
        );

        $advertIds = array_map(
            function($item) {
                return $item->id;
            },
            array_filter($paginator->items(),
                function($item) {
                    return $item['_feed_type'] === 'advert';
                }
            )
        );

        if(count($advertIds) > 0)
            Advert::whereIn('id', $advertIds)->increment('recommended_impressions');

        return $paginator;
    }

    public function index()
    {
        $user = Auth::user();

        if($user->isCompany())
            return view('company.dashboard', ['items' => $this->dashCompany()]);
        else
            return view('employee.dashboard', ['items' => $this->dashEmployee()]);
    }

    /**
     * @throws \Throwable
     */
    public function get()
    {
        $user = Auth::user();

        if($user->isCompany())
            $paginator = $this->dashCompany();
        else
            $paginator = $this->dashEmployee();

        return view('employee._dash-collection', ['items' => $paginator->items()])->render();
    }
}
