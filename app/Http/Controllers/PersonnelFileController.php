<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class PersonnelFileController extends Controller
{
    public function generate()
    {
        $profile = Auth::user()->profile;
        return PDF::loadView('pdf.personnel', ['profile' => $profile])
            ->setPaper('a4')
            ->setOrientation('portrait')
            ->setOption('margin-bottom', 0)
            ->inline($profile->first_name . $profile->last_name . '-personnel-file.pdf');
    }
}
