<?php

namespace App\Collectors;

use App\Classes\Collector;

class HitsTotalCollector extends Collector
{
	protected $expects = ['hitgroup', 'attackerSteam'];
	protected $acceptedEvents = ['PlayerDamage'];
	protected $eventOwnerKey = ['PlayerDamage' => 'attackerSteam'];

	public function collect($event): void
	{
		$session = $this->manager->getSession();

		$hitgroup = $event['hitgroup'];

		$this->command('INCRBY', "hits.$hitgroup", [1]);
		info("HitsTotalCollector adding 1 hit [$hitgroup] to $session->steamid on session $session->id", compact('event', 'session'));
	}
}