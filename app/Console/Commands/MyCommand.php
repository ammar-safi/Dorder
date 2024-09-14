<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is my command';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $x = $this->ask("Hello");
        echo $x ;
        Log::info('My command executed successfully');
    }

}
