<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class IncrementVCS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vcs:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Increment every Virtual Currency Wallet 0.25';

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
        DB::update("UPDATE users_vcs set amount = amount + 0.25");
    }
}
