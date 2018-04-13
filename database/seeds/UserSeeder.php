<?php

use App\Company;
use App\Cv\Cv;
use App\Profile;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companyUser = factory(App\User::class)->create([
            'email' => 'employer@karma.com',
        ]);

        $profile1 = new Profile();
        $profile1->first_name = 'James';
        $profile1->last_name = 'Waring';

        $company = new Company();
        $company->name = 'Karma AS';
        $company->phone = '123-4567-8901';
        $company->contact_email = 'careers@karma.com';
        $company->created_by_user_id = $companyUser->id;
        $company->save();

        $companyUser->company_id = $company->id;
        $companyUser->save();
        $companyUser->profile()->save($profile1);

        ///

        $employeeUser = factory(App\User::class)->create([
            'email' => 'employee@karma.com',
        ]);

        $profile2 = new Profile();
        $profile2->first_name = 'Luke';
        $profile2->last_name = 'Ramsden';
        $employeeUser->profile()->save($profile2);

        $cv = new Cv();
        $employeeUser->cv()->save($cv);
    }
}
