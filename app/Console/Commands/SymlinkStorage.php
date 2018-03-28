<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SymlinkStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'symlink';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Symlinks storage to be publicly accessible';

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
        $this->question(getcwd());
        if ($this->confirm('Is this your project path?')) {
            try {
                symlink(getcwd() . '/storage/app/', getcwd() . '/public/storage');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }
}
