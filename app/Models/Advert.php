<?php

namespace App\Models;

use App\Models\Location;
use Illuminate\Database\Eloquent\Model;

class Advert extends Model
{
    
    protected $guarded = ['_token', 'id'];

    private $roles = [
        '1' => 'Senior Carer / Head of Care / Team Leader',
        '2' => 'Carer / Care Assistant / Care Support Worker',
        '3' => 'Bank Carer / Care Assistant',
        '4' => 'Activity Coordinator',
        '5' => 'Clinical Lead / Nurse Team Leader',
        '6' => 'Staff Nurse',
        '7' => 'Nurse',
        '8' => 'Bank Nurse',
        '9' => 'Registered Manager / Service Manager',
        '10' => 'Deputy Manager / Assistant Manager',
        '11' => 'Area / Regional Manager',
        '12' => 'Director / Divisional Director',
        '13' => 'Social Worker',
        '14' => 'Physiotherapist',
        '15' => 'Occupational Therapist',
        '16' => 'Training & Development',
        '17' => 'Psychologist',
        '18' => 'Receptionist',
        '19' => 'Administrator',
        '20' => 'Finance / Accountant / Bookkeeper',
        '21' => 'Human Resources / HR / Recruitment',
        '22' => 'Marketing',
        '23' => 'Cook / Chef',
        '24' => 'Kitchen Assistant / Catering Assistant',
        '25' => 'Housekeeper / Cleaner',
        '26' => 'Domestic / Domestic Assistant',
        '27' => 'Laundry Assistant',
        '28' => 'Maintenance / Handyperson',
        '29' => 'Driver',
        '30' => 'Gardener',
        '31' => 'Other'
    ];

    private $settings = [
        '1' => 'Care Home / Nursing Home',
        '2' => 'Housing with Care',
        '3' => 'Hospital',
        '4' => 'Adult Day Care Centre',
        '5' => 'Charities / Association / Org',
        '6' => 'Care Provider HQ / Office',
        '7' => 'Other',
    ];

    private $types = [
        '1' => 'Full Time',
        '2' => 'Part Time',
        '3' => 'Full Time or Part Time',
        '4' => 'Temporary / Cover',
        '5' => 'Contract',
        '6' => 'Voluntary',
    ];

    public function getRoles()
    {
        sort($this->roles);
        return $this->roles;
    }

    public function getSettings()
    {
        sort($this->settings);
        return $this->settings;
    }

    public function getTypes()
    {
        return $this->types;
    }

    public function getAllLocations()
    {
        $locations = Location::select(['id', 'name', 'county'])
            ->whereIn('type', ['City', 'Town', 'Village'])
            ->get();

        $locs = [];

        foreach ($locations as $loc) {
            $locs[] = (object) [
                'id' => $loc->id,
                'name' => str_replace("'", '', $loc->name) .' ('. $loc->county .')',
            ];
        }  

        $locs = collect($locs)->unique('name')->all();

        return $locs;
    }

    public function linkEdit()
    {
        return route('advert_edit', [
            $this->id
        ]);
    }

}
