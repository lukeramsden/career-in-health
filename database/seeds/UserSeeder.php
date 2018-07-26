<?php

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
        $this->createEmployeeUser();
        $this->createCompanyUser();
    }

    private function createEmployeeUser()
    {
        $user = factory(\App\User::class)
            ->create([
                'email' => 'employee@karma.com',
            ]);

        $userable = new \App\Employee([
            'first_name'  => 'Luke',
            'last_name'   => 'Ramsden',
            'location_id' => 4018,
        ]);

        $userable->save();
        $user->userable()->associate($userable);
        $user->save();
    }

    private function createCompanyUser()
    {
        $user = factory(\App\User::class)
            ->create([
                'email' => 'company@karma.com',
            ]);

        $userable = new \App\CompanyUser([
            'first_name'      => 'James',
            'last_name'       => 'Waring',
            'has_been_filled' => true,
        ]);

        $userable->save();
        $user->userable()->associate($userable);
        $user->save();

        $company = factory(\App\Company::class)
            ->create([
                'name'               => 'Karma AS',
                'created_by_user_id' => $user->id,
                'location_id'        => 4018,
            ]);

        $company->users()->save($userable);
        $company->save();
        $userable->save();
        $user->save();

		DB
			::table('company_user_permissions')
			->insert([
				'company_id'       => $company->id,
				'company_user_id'  => $user->userable_id,
				'permission_level' => 'owner',
			]);
    }
}
