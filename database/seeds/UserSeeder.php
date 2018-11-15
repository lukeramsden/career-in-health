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
	self::createEmployeeUser();
	self::createCompanyUser();
//		self::createAdvertiserUser();
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
	  'about'       => '15 year old software engineer from Blackpool. Future leader of the galaxy.',
	]);

	$userable->save();
	$user->userable()->associate($userable);
	$user->save();

	$preferences                      = $userable->cv->preferences;
	$preferences->job_role            = 4;
	$preferences->type                = 1;
	$preferences->willing_to_relocate = true;
	$preferences->save();

	$userable->cv->education()->save(new \App\Cv\CvEducation([
	  'degree'         => 'Bachelor\'s',
	  'school_name'    => 'Keele University',
	  'field_of_study' => 'Business and Management',
	  'location'       => 'Newcastle',
	  'start_date'     => '2019-01-01',
	  'end_date'       => '2021-01-01',
	]));

	$userable->cv->workExperience()->save(new \App\Cv\CvWorkExperience([
	  'job_title'    => 'Full-Stack Web Developer',
	  'company_name' => 'Karma AS',
	  'description'  => 'Worked as a full-stack web developer on "Career In Health", a jobs and recruiting platform for the health and care industries.',
	  'start_date'   => '2018-02-01',
	  'location'     => 'Blackpool',
	]));
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
		'name'        => 'Karma AS',
		'owner_id'    => $userable->id,
		'location_id' => 4018,
		'verified'    => true,
	  ]);

	$company->users()->save($userable);
	$company->save();
	$userable->save();
	$user->save();

	DB
	  ::table('company_user_permissions')
	  ->insert([
		'company_id'       => $company->id,
		'company_user_id'  => $userable->id,
		'permission_level' => 'owner',
	  ]);
  }

  private function createAdvertiserUser()
  {
	$user = factory(\App\User::class)
	  ->create([
		'email' => 'advertiser@karma.com',
	  ]);

	$userable = new \App\Advertising\Advertiser([
	  'name' => 'Sponsor',
	]);

	$userable->save();
	$user->userable()->associate($userable);
	$user->save();
  }
}
