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
        // bunch of back-and-forth conversation
        foreach(range(1,4) as $index)
        {
            factory(\App\PrivateMessage::class, 1)->create([
                'from_user_id' => 1,
                'to_user_id' => 2,
                'advert_id' => 1,
            ]);
            factory(\App\PrivateMessage::class, 1)->create([
                'from_user_id' => 2,
                'to_user_id' => 1,
                'advert_id' => 1,
            ]);
        }

        // same but for a different advert
        foreach(range(1,4) as $index)
        {
            factory(\App\PrivateMessage::class, 1)->create([
                'from_user_id' => 1,
                'to_user_id' => 2,
                'advert_id' => 2,
            ]);
            factory(\App\PrivateMessage::class, 1)->create([
                'from_user_id' => 2,
                'to_user_id' => 1,
                'advert_id' => 2,
            ]);
        }
    }
}
