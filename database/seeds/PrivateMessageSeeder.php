<?php

use Illuminate\Database\Seeder;

class PrivateMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $messages1to2 = factory(\App\PrivateMessage::class, 20)->create([
            'from_user_id' => 1,
            'to_user_id'   => 2,
            'advert_id'    => random_int(1, 150)
        ]);

        $messages2to1 = factory(\App\PrivateMessage::class, 20)->create([
            'from_user_id' => 2,
            'to_user_id'   => 1,
            'advert_id'    => random_int(1, 150)
        ]);
    }
}
