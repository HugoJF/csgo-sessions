<?php

namespace App\Stats\Misc;

use App\Classes\Stat;
use App\Stats\Kills\TotalKillsStat;
use App\Stats\Deaths\TotalDeathsStat;

class KillsPerMinStat extends Stat
{
	protected $dependencies = [TotalKillsStat::class, DurationStat::class];
	protected $name = 'kills-per-min';

	function compute()
	{
		/** @var TotalKillsStat $killStat */
		$killStat = $this->getStat(TotalKillsStat::class);
		/** @var DurationStat $durationStat */
		$durationStat = $this->getStat(DurationStat::class);

		$kills = $killStat->getValue();
		$duration = $durationStat->getValue();

		if ($duration > 0)
			return $kills / $duration;
		else
			return 0;
	}
}