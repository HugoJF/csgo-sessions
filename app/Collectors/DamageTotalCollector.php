<?php

namespace App\Collectors;

use App\Classes\Collector;

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
	}
}