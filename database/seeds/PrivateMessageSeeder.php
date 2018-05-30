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
        $messages = factory(\App\PrivateMessage::class, 20)->create();
    }
}
