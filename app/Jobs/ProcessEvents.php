<?php

namespace App\Jobs;

use App\Classes\EventDispatcher;
use App\Classes\SessionManager;
use App\Services\EventService;
use App\Services\SessionService;
use App\Session;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class ProcessEvents implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$eventDispatcher = new EventDispatcher();

		for ($i = 0; $i < 1000; $i++) {
			$rawEvent = Redis::connection('input')->rpop('sessions');
			$event = json_decode($rawEvent);

			if (!$event) {
				info('Null event found', compact('rawEvent', 'event'));

				return;
			}

			$eventDispatcher->dispatchEvent($event);
		}
	}

}
