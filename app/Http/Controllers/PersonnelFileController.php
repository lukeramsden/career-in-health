<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class PersonnelFileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('only.employee');
    }

    public function view(Request $request) {
        return view('pdf.personnel', ['profile' => Auth::user()->profile, 'download' => $request->query('embed', false)]);
    }

    public function download()
    {
        $profile = Auth::user()->profile;
        return PDF::loadView('pdf.personnel', ['profile' => $profile, 'download' => true])
            ->setPaper('a4')
            ->setOrientation('portrait')
            ->setOption('margin-bottom', 0)
            ->inline($profile->first_name . $profile->last_name . '-personnel-file.pdf');
    }
}
