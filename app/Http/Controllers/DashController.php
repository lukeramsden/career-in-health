<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        if($user->isCompany())
        {
            $applications = $user->company
                ->applications()
                ->orderBy('updated_at', 'desc')
                ->get();

            return view('company_home')
                ->with(['applications' => $applications]);
        }

        return view('home');
    }
}
