<?php

namespace App\Http\Controllers;

use App\Advert;
use App\AdvertStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function employer_dash()
    {
        $user = Auth::user();
        $applications = $user->company
            ->applications()
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('kitchen-sink')
            ->with(['applications' => $applications]);
    }

    private function employee_dash()
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

        return view('employee.dashboard', ['feed' => $feed->sortByDesc('updated_at')]);
    }

    public function index()
    {
        $user = Auth::user();

        return $user->isCompany()
            ? $this->employer_dash()
            : $this->employee_dash();
    }
}
