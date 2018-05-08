<?php

namespace App\Http\Controllers;

use App\Advert;
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

        $applications = $user
            ->applications()
            ->get()
            ->map(function ($item, $key) {
                $item['_feed_type'] = 'application';
                return $item;
            });
        $adverts = Advert
            ::where('company_id', '1')
            ->get()
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
