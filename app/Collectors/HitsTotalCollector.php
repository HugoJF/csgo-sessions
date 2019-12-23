<?php

namespace App\Collectors;

use App\Classes\Collector;

class HitsTotalCollector extends Collector
{
	protected $expects = ['hitgroup', 'weapon', 'attackerSteam'];
	protected $acceptedEvents = ['PlayerDamage'];
	protected $eventOwnerKey = ['PlayerDamage' => 'attackerSteam'];

	public function collect($event): void
	{
		$session = $this->manager->getSession();

		$hitgroup = $this->getCleanKey($event['hitgroup']);
		$weapon = $this->getCleanKey($event['weapon']);

		$this->command('INCRBY', "hits.$weapon.$hitgroup", [1]);
//		info("HitsTotalCollector adding 1 hit [$hitgroup] to $session->steamid on session $session->id", compact('event', 'session'));
	}
}