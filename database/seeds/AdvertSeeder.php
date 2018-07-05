<?php

use App\Advert;
use Illuminate\Database\Seeder;

class AdvertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adverts = factory(Advert::class, 150)->create([
            'company_id' => 1,
            'created_by_user_id' => 3,
            'published' => true,
        ]);

		$company = \App\Company::find(1);

		$company->has_created_first_advert = true;
		$company->save();
    }
}
