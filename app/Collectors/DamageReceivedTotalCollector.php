<?php

namespace App\Collectors;

use App\Classes\Collector;

class DamageReceivedTotalCollector extends Collector
{
	protected $expects = ['damage', 'hitgroup', 'targetSteam'];
	protected $acceptedEvents = ['PlayerDamage'];
	protected $eventOwnerKey = ['PlayerDamage' => 'targetSteam'];

	public function collect($event): void
	{
		$session = $this->manager->getSession();

		$damage = $event['damage'];

		$hitgroup = $this->getCleanKey($event['hitgroup']);

		$this->command('INCRBY', "damage.$hitgroup", [$damage]);
		info("DamageTotalCollector adding $damage [$hitgroup] HP to $session->steamid on session $session->id", compact('event', 'session'));
	}
}