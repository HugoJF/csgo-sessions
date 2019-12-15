<?php

namespace App\Console\Commands;

use App\Classes\EventDispatcher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class ProcessEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
		$eventDispatcher = app(EventDispatcher::class);

		for ($i = 0; $i < 1000; $i++) {
			$rawEvent = Redis::connection('input')->lpop('sessions');
			$event = json_decode($rawEvent, true);

			$eventDispatcher->dispatchEvent($event);
		}
    }
}