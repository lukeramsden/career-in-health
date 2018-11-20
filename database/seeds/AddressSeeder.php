<?php

use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
	factory(\App\Address::class)
	  ->create([
		'company_id'  => 1,
		'location_id' => 4018,
		'name'        => 'Peaceful Acres Carehome',
	  ]);

	factory(\App\Address::class)
	  ->create([
		'company_id'  => 1,
		'location_id' => 4018,
		'name'        => 'Victoria Hospital',
	  ]);
  }
}
