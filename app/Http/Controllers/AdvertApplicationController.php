<?php

namespace App\Http\Controllers;

use App\Models\Advert;
use App\Models\AdvertApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvertApplicationController extends Controller
{
    private function getValidateRules(bool $internal)
    {
        return $internal ? [
                    'status' => 'nullable|integer'
                ] : [
                    'custom_cover_letter' => 'nullable|string|max:3000',
                ];
    }

    public function create(Advert $advert)
    {
        if(Auth::guest()) {
            session(['apply_to_job_id' => $advert->id]);
            return redirect(route('register'));
        }

        if(AdvertApplication::alreadyApplied(Auth::user(), $advert))
        {
            return redirect(route('advert.show', ['advert' => $advert]))
                        ->with([
                            'status' => 'You have already applied to this job!'
                        ]);
        }

        return view('advert.apply')
            ->with(['advert' => $advert]);
    }

    public function store(Request $request, Advert $advert)
    {
        if(AdvertApplication::alreadyApplied(Auth::user(), $advert))
        {
            return redirect(route('advert.show', ['advert' => $advert]))
                        ->with([
                            'status' => 'You have already applied to this job!'
                        ]);
        }
        
        $data = $request->validate($this->getValidateRules(false));

        $application = new AdvertApplication();
        $application->user_id = Auth::user()->id;
        $application->fill($data);
        $advert->applications()->save($application);

        return redirect(route('advert.show', ['advert' => $advert]))
            ->with([
                'status' => 'Applied!'
            ]);
    }

    public function update(Request $request, AdvertApplication $application)
    {
        if ($request->has('status')) {
            $user = Auth::user();
            if(!$user->isCompany() || $application->advert->company_id !== $user->company_id) {
                return response()->json(['success' => false, 'message' => 'You must own the advert to update an application\'s status']);
            } else {
                $data = $request->validate($this->getValidateRules(true));

                $application->status = $data['status'];
            }
        } else {
            $data = $request->validate($this->getValidateRules(false));

            $application->fill($data);
        }

        $application->save();

        return response()->json(['success' => true]);
    }
}
