<?php

namespace App\Http\Controllers;

use App\Models\Advert;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    static $validation = [
        'town' => 'required|integer|exists:locations,id'
    ];

    public function search(Request $request)
    {
        $data = $request->all();//validate($this::$validation);

        if($request->has('town')) {
            $town = Location::find($request->town);
            $results = Advert::query();
            $results = $results->whereHas('address', function($query) use($town) {
                $query->whereHas('location', function($query) use($town) {
                    $query->whereRaw('( 3959 * acos( cos( radians(?) ) * cos( radians( latitude ) )
                        * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin(radians(latitude)) ) ) < ?', [
                        $town->latitude,
                        $town->longitude,
                        $town->latitude,
                        5000,
                    ]);
                });
            });

            $results = $results->paginate(10);

            return view('search')
                ->with([
                    'results' => $results,
                    'town' => $town
                ]);
        }

        return view('search');

//        if($request->has('title'))
//            $results->where('title', 'LIKE', '%'.$data['title'].'%');

    }
}
