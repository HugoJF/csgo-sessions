<?php

namespace App\Stats\Misc;

use App\Classes\Stat;
use App\Stats\Kills\TotalKillsStat;
use App\Stats\Deaths\TotalDeathsStat;

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

		$kills = $killStat->getValue();
		$deaths = $deathStat->getValue();

		if ($deaths > 0)
			return $kills / $deaths;
		else
			return 0;
	}
}