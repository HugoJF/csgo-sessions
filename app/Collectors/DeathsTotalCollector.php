<?php

namespace App\Collectors;

use App\Classes\Collector;

class DeathsTotalCollector extends Collector
{
	protected $expects = ['damage', 'targetHealth', 'hitgroup', 'targetSteam'];
	protected $acceptedEvents = ['PlayerDamage'];
	protected $eventOwnerKey = ['PlayerDamage' => 'targetSteam'];

	public function collect($event): void
	{
		$session = $this->manager->getSession();

		$targetHealth = $event['targetHealth'];

		if ($targetHealth != 0)
			return;

		$weapon = $this->getCleanKey($event['weapon']);
		$hitgroup = $this->getCleanKey($event['hitgroup']);

		$this->command('INCRBY', "deaths.$weapon.$hitgroup", [1]);
		info("DamageTotalCollector adding 1 [$hitgroup] death to $session->steamid on session $session->id", compact('event', 'session'));
	}
}