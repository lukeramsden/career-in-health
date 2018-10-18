<?php

use App\JobListing;
use Illuminate\Database\Seeder;

class JobListingSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 * @throws Throwable
	 */
	public function run()
	{
		DB::transaction(function() {
			factory(JobListing::class, 500)->create([
				'company_id'         => 1,
				'created_by_user_id' => 3,
			]);

			$company = \App\Company::find(1);

			$company->has_created_first_job_listing = true;
			$company->save();
		});
	}
}
