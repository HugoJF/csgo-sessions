<?php

namespace App\Stats\Misc;

use App\Classes\Stat;
use App\Stats\Damage\TotalDamageStat;
use App\Stats\Kills\TotalKillsStat;
use App\Stats\Deaths\TotalDeathsStat;

class DamagePerMinStat extends Stat
{
	protected $dependencies = [TotalDamageStat::class, DurationStat::class];
	protected $name = 'damage-per-min';

	function compute()
	{
		/** @var TotalDamageStat $damageStat */
		$damageStat = $this->getStat(TotalDamageStat::class);
		/** @var DurationStat $durationStat */
		$durationStat = $this->getStat(DurationStat::class);

		$damage = $damageStat->getValue();
		$duration = $durationStat->getValue();

		if ($duration > 0)
			return $damage / $duration;
		else
			return 0;
	}
}