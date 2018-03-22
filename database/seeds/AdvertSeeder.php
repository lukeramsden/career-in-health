<?php

use App\Models\Advert;
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
        $adverts = factory(Advert::class, 50)->create([
            'company_id' => 1,
            'created_by_user_id' => 1,
        ]);
    }
}