<?php

namespace App\Stats;

use App\Classes\Stat;

class KdrStat extends Stat
{
	protected $dependencies = [TotalKillsStat::class, TotalDeathsStat::class];
	protected $name = 'kdr';

	function compute()
	{
		/** @var TotalKillsStat $killStat */
		$killStat = $this->getStat(TotalKillsStat::class);
		/** @var TotalDeathsStat $deathStat */
		$deathStat = $this->getStat(TotalDeathsStat::class);

		return $killStat->getValue() / $deathStat->getValue();
	}
}