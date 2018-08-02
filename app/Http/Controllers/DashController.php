<?php

namespace App\Http\Controllers;

use App\JobListing;
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
        $this->middleware('company-created');
        $this->middleware('must-onboard');
    }

    protected function dashCompany()
    {
        $user = Auth::user();
        $companyUser = $user->userable;
        $company = $companyUser->company;

        $currentPage = $this->request->get('page', 1);

        $applicationsCount =
            $company
                ->applications()
                ->count();

        $applications =
            $company
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
        $user = Auth::user();
        $employee = $user->userable;

        $currentPage = $this->request->get('page', 1);

        // Applications
        $applicationsCount =
            $employee
                ->applications()
                ->count();

        $applications =
            $employee
                ->applications()
                ->orderByDesc('updated_at')
                ->skip(self::$perPage * ($currentPage - 1))
                ->take(self::$perPage)
                ->get()
                ->map(function ($item, $key) {
                    $item['_feed_type'] = 'application';
                    return $item;
                });

        // JobListings

        $jobListings =
            JobListing
                ::wherePublished(true)
                ->with(['jobRole', 'company', 'address']);

        if(isset(optional($user)->cv->preferences->job_role))
            $jobListings->whereJobRole($user->cv->preferences->job_role);

        if(isset(optional($user)->cv->preferences->setting))
            $jobListings->whereSetting($user->cv->preferences->setting);

        if(isset(optional($user)->cv->preferences->type))
            $jobListings->whereType($user->cv->preferences->type);

        $jobListingsCount = $jobListings->count();
        $jobListings =
            $jobListings
                ->inRandomOrder()
                ->skip(self::$perPage * ($currentPage - 1))
                ->take(self::$perPage)
                ->get()
                ->map(function ($item, $key) {
                    $item['_feed_type'] = 'job_listing';
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
        $jobListings         = collect($jobListings);
//        $privateMessages = collect($privateMessages);
        $feed            = collect([]);

        $count  = $applicationsCount;
        $count += $jobListingsCount;
//        $count += $privateMessagesCount;

        if($jobListingsCount > 0 && $applicationsCount > 0)
            while($feed->count() < self::$perPage)
            {
                $item = null;
                switch(random_int(0, 1)) {
                    case 0:
                        $item = $jobListings->shift();
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

        $jobListingIds = array_map(
            function($item) {
                return $item->id;
            },
            array_filter($paginator->items(),
                function($item) {
                    return $item['_feed_type'] === 'job_listing';
                }
            )
        );

        if(count($jobListingIds) > 0)
            JobListing::whereIn('id', $jobListingIds)->increment('recommended_impressions');

        return $paginator;
    }

    public function index()
    {
        $user = Auth::user();

        if($user->isCompany())
            return view('company.dashboard', ['items' => self::dashCompany()]);
		elseif($user->isEmployee())
            return view('employee.dashboard', ['items' => self::dashEmployee()]);
		else
			return view('layouts.app');
    }

    /**
     * @throws \Throwable
     */
    public function get()
    {
        $user = Auth::user();

        if($user->isCompany())
            $paginator = $this->dashCompany();
        elseif($user->isEmployee())
            $paginator = $this->dashEmployee();
        else
        	abort(403);

        return view('employee._dash-collection', ['items' => $paginator->items()])->render();
    }
}
