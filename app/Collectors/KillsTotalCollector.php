<?php

namespace App\Collectors;

use App\Classes\Collector;

class KillsTotalCollector extends Collector
{
	protected $expects = ['damage', 'targetHealth', 'hitgroup', 'attackerSteam'];
	protected $acceptedEvents = ['PlayerDamage'];
	protected $eventOwnerKey = ['PlayerDamage' => 'attackerSteam'];

	public function collect($event): void
	{
		$session = $this->manager->getSession();

		$targetHealth = $event['targetHealth'];

		if ($targetHealth !== 0)
			return;

		$weapon = $this->getCleanKey($event['weapon']);
		$hitgroup = $this->getCleanKey($event['hitgroup']);

		$this->command('INCRBY', "kills.$weapon.$hitgroup", [1]);
		info("DamageTotalCollector adding 1 [$hitgroup] kill to $session->steamid on session $session->id", compact('event', 'session'));
	}
}