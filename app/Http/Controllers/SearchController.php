<?php

namespace App\Http\Controllers;

use App\Models\Advert;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    static $validation = [
        'town' => 'required|integer|exists:locations,id',
        'radius' => 'required|integer|min:10|max:500',
        'job_types' => 'array',
        'job_types.*' => 'integer|distinct|exists:job_types,id',
        'min_salary' => 'required|integer|min:0|max:150000',
        'max_salary' => 'required|integer|min:1|max:150000|greater_than_field:min_salary',
        'setting_filter' => 'array',
        'setting_filter.*' => 'integer|distinct',
        'type_filter' => 'array',
        'type_filter.*' => 'integer|distinct',
    ];

    public function search(Request $request)
    {
        if($request->has('town')) {
            $data = $request->validate(self::$validation);
            $town = Location::find($request->town);
            $results = Advert::query();
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

            if(isset($data['job_types']))
                $results->whereIn('job_type_id', $request->job_types);

            if(isset($data['min_salary']))
                $results->where('max_salary', '>', $request->min_salary);

            if(isset($data['max_salary']))
                $results->where('max_salary', '<', $request->min_salary);

            if(isset($data['setting_filter']))
                $results->whereIn('setting', $request->setting_filter);

            if(isset($data['type_filter']))
                $results->whereIn('type', $request->type_filter);

            $results = $results->orderBy('max_salary', 'desc')->paginate(10);

            return view('search')
                ->with([
                    'results' => $results,
                    'town' => $town
                ]);
        }

        return view('search');
    }
}
