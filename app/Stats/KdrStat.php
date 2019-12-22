<?php

namespace App\Stats;

use App\Classes\Stat;

class KdrStat extends Stat
{
	protected $dependencies = [KillStat::class, DeathStat::class];
	protected $name = 'kdr';

	function compute()
	{
		/** @var KillStat $killStat */
		$killStat = $this->getStat(KillStat::class);
		/** @var DeathStat $deathStat */
		$deathStat = $this->getStat(DeathStat::class);

		return $killStat->getValue() / $deathStat->getValue();
	}
}