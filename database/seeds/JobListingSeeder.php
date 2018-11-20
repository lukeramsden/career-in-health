<?php

use App\JobListing;
use Carbon\Carbon;
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
	factory(JobListing::class)
	  ->create([
		'company_id'         => 1,
		'created_by_user_id' => 3,
		'address_id'         => 1,
		'title'              => 'Cleaner Urgently Needed For Care Home',
		'description'        => 'Need temporary cleaner urgently for our care home',
		'job_role'           => 83,
		'setting'            => 1,
		'type'               => 4,
		'min_salary'         => null,
		'max_salary'         => 20000,
		'published'          => true,
		'closed_at'          => null,
		'close_reason'       => null,
		'created_at'         => Carbon::now(),
		'updated_at'         => Carbon::now(),
		'last_edited'        => Carbon::now(),
	  ]);

	factory(JobListing::class)
	  ->create([
		'company_id'         => 1,
		'created_by_user_id' => 3,
		'address_id'         => 2,
		'title'              => 'Looking for full-time assistant surgeon',
		'description'        => "We are lookng for a full-time surgeon to assist our lead surgeon at this hospital\nMust be experienced",
		'job_role'           => 156,
		'setting'            => 3,
		'type'               => 1,
		'min_salary'         => 60000,
		'max_salary'         => 100000,
		'published'          => true,
		'closed_at'          => null,
		'close_reason'       => null,
		'created_at'         => Carbon::now(),
		'updated_at'         => Carbon::now(),
		'last_edited'        => Carbon::now(),
	  ]);

	$company = \App\Company::find(1);

	$company->has_created_first_job_listing = true;
	$company->save();
  }
}
