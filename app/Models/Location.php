<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    
    public static function LoadCSV()
    {
        ini_set('memory_limit','-1');
        ini_set('max_execution_time', 10000);

        // Location::where('id', '>', 0)->delete();

        $csv = array_map('str_getcsv', file(base_path().'/storage/app/public/uk-towns.csv'));

        $keys = $csv[0];
        unset($csv[0]);

        $count = 0;
        $temp = [];

        foreach ($csv as $line) {
            $t = [];

            foreach ($keys as $k => $v) {
                $t[$v] = trim(explode(' / ', $line[$k])[0]);
            }

            unset($t['id']);
            $temp[] = $t;

            $count++;

            if ($count >= 900) {
                Location::insert($temp);
                unset($temp);

                $temp = [];
                $count = 0;
            }
        }

        if (count($temp) > 0) {
            Location::insert($temp);
        }
    }

}
