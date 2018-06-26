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
            'first_name' => 'Luke',
            'last_name' => 'Ramsden'
        ]);

        $userable->save();
        $user->userable()->associate($userable);
        $user->save();
    }

    private function createCompanyUser()
    {
        
    }
}
