<?php

use App\JobRole;
use Illuminate\Database\Seeder;

class JobRoleSeeder extends Seeder
{
    public function run()
    {
        /** @var string $item */
        foreach (JobRole::$list as $item)
        {
            $job = new JobRole();
            $job->name = $item;
            $job->save();
        }
    }
}
