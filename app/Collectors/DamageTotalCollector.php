<?php

namespace App\Collectors;

use App\Classes\Collector;

class DamageTotalCollector extends Collector
{
	protected $expects = ['damage'];
	protected $acceptedEvents = ['PLAYER_DAMAGE'];

	public function collect($event): void
	{
		$damage = $event['damage'];

		$this->command('damage', 'INCRBY', [$damage]);
	}
}