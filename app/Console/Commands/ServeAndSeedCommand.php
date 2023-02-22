<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand;

class ServeAndSeedCommand extends ServeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'serve:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        parent::handle();
        $this->call('db:seed');
    }
}