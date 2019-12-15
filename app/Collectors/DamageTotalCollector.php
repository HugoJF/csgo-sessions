<?php

namespace App\Collectors;

use App\Classes\Collector;
use Illuminate\Support\Facades\Log;

class DamageTotalCollector extends Collector
{
	protected $expects = ['damage'];
	protected $acceptedEvents = ['PlayerDamage'];

	public function collect($event): void
	{
		$steamid = $this->session->getSession()->steamid;

		// TODO: ew
		if (($event['attackerSteam'] ?? null) !== $steamid)
			return;

		$damage = $event['damage'];

		$this->command('damage', 'INCRBY', [$damage]);
		$id = $this->session->getSession()->id;
		$session = $this->session->getSession();
		Log::info("DamageTotalCollector adding $damage HP to $steamid on session $id", compact('event', 'session'));
	}
}