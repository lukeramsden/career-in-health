<?php

use App\Models\JobType;
use Illuminate\Database\Seeder;

class JobTypeSeeder extends Seeder
{
    public function run()
    {
        /** @var string $item */
        foreach (JobType::$list as $item)
        {
            $job = new JobType();
            $job->name = $item;
            $job->save();
        }
    }
}
