<?php

namespace App\Http\Controllers;

use App\Advert;
use App\AdvertStatus;
use App\Location;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct()
    {
        ///
    }

    static $validation = [
        'town' => 'required|integer|exists:locations,id',
        'radius' => 'nullable|integer|min:5|max:50',
        'job_roles' => 'array',
        'job_roles.*' => 'integer|distinct|exists:job_roles,id',
        'min_salary' => 'nullable|integer|min:0|max:150000|less_than_field:max_salary',
        'max_salary' => 'nullable|integer|min:1|max:150000|greater_than_field:min_salary',
        'setting_filter' => 'array',
        'setting_filter.*' => 'integer|distinct',
        'type_filter' => 'array',
        'type_filter.*' => 'integer|distinct',
    ];

    public function search(Request $request)
    {
        if($request->has('town')) {
            $data = $request->validate(self::$validation);
            $town = Location::find($data['town']);
            $results = Advert::with('address', 'address.location', 'jobRole', 'company');

            if(isset($data['radius']) && $data['radius'] < 50) {
                $results = $results->whereHas('address', function($q) use($town, $data) {
                    $q->whereHas('location', function($q) use($town, $data) {
                        $q->whereRaw('(3959 * acos(cos(radians(?)) * cos(radians( latitude )) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) < ?', [
                            $town->latitude,
                            $town->longitude,
                            $town->latitude,
                            $data['radius'],
                        ]);
                    });
                });
            }

            if(isset($data['job_roles']))
                $results->whereIn('job_role', $data['job_roles']);

            if(isset($data['min_salary']))
                $results->where('max_salary', '>', $data['min_salary']);

            if(isset($data['max_salary']) && $data['max_salary'] < 150000)
                $results->where('min_salary', '<', $data['max_salary']);

            if(isset($data['setting_filter']))
                $results->whereIn('setting', $data['setting_filter']);

            if(isset($data['type_filter']))
                $results->whereIn('type', $data['type_filter']);

            $results = $results->where('status', AdvertStatus::Public)->orderBy('max_salary', 'desc')->paginate(10);

            // impressions tracking
            foreach($results->items() as $advert)
                $advert->increment('search_impressions');

            return view('search')
                ->with([
                    'results' => $results,
                    'town' => $town
                ]);
        }

        return view('search')
            ->with([
                'isAdvanced' => $request->query('advanced')
            ]);
    }
}
