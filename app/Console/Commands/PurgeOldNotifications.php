<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;

class PurgeOldNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purge:old-read-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purges read notifications that were read more than 7 days ago.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $num = DB::table('notifications')
			->whereNotNull('read_at')
			->where('read_at', '<=', Carbon::now()->subDays(7)->toDateTimeString())
			->delete();

        $this->info("Delete $num rows.");
    }
}
