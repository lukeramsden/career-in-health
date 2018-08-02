<?php

use App\JobListing;
use Illuminate\Database\Seeder;

class JobListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jobListings = factory(JobListing::class, 150)->create([
            'company_id' => 1,
            'created_by_user_id' => 3,
            'published' => true,
        ]);

		$company = \App\Company::find(1);

		$company->has_created_first_job_listing = true;
		$company->save();
    }
}
