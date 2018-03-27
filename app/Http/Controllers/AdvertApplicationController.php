<?php

namespace App\Http\Controllers;

use App\Models\Advert;
use App\Models\AdvertApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvertApplicationController extends Controller
{
    static $validation = [
        'custom_cover_letter' => 'nullable|string|max:3000',
    ];

    public function create(Advert $advert)
    {
        if(Auth::guest()) {
            session(['apply_to_job_id' => $advert->id]);
            return redirect(route('register'));
        }

        return view('advert.apply')
            ->with(['advert' => $advert]);
    }

    public function store(Request $request, Advert $advert)
    {
        $data = $request->validate($this::$validation);

        $application = new AdvertApplication();
        $application->fill($data);
        $advert->applications()->save($application);

        return redirect(route('advert.show', ['advert' => $advert]))
            ->with([
                'status' => 'Applied!'
            ]);
    }
}
