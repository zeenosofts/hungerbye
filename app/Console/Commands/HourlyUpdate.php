<?php

namespace App\Console\Commands;

use App\Http\Controllers\ActionController;
use Illuminate\Console\Command;

class HourlyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hourly:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Hourly Update';

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
        //
        ActionController::render_this_function();
        $this->info('hourly:update Cummand Run successfully!');
    }
}
