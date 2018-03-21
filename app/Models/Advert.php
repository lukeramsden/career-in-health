<?php

namespace App\Models;

use App\Models\Location;
use Illuminate\Database\Eloquent\Model;

class Advert extends Model
{
    protected $with = ['address'];

    protected $guarded = ['_token', 'id', 'save_for_later'];

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

    public function linkEdit()
    {
        return route('advert_edit', [
            $this->id
        ]);
    }

    public function address()
    {
        return $this->hasOne('App\Models\Address', 'id', 'address_id');
    }

    public function getDistanceToLocation(Location $location)
    {
        $lat1 = $this->address->location->latitude;
        $lon1 = $this->address->location->longitude;
        $lat2 = $location->latitude;
        $lon2 = $location->longitude;

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        return $miles;
    }

    public function getDistanceToAddress(Address $address)
    {
        return $this->getDistanceToLocation($address->location);
    }
}
