<?php

namespace App\Collectors;

use App\Classes\Collector;

class DamageTotalCollector extends Collector
{
	protected $expects = ['damage', 'hitgroup', 'attackerSteam'];
	protected $acceptedEvents = ['PlayerDamage'];
	protected $eventOwnerKey = ['PlayerDamage' => 'attackerSteam'];

	public function collect($event): void
	{
		$session = $this->manager->getSession();

		$damage = $event['damage'];
		$hitgroup = $event['hitgroup'];

		$this->command('INCRBY', "damage.$hitgroup", [$damage]);
		info("DamageTotalCollector adding $damage [$hitgroup] HP to $session->steamid on session $session->id", compact('event', 'session'));
	}
}