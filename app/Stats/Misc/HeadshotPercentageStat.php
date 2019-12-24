<?php

namespace App\Stats\Misc;

use App\Classes\Stat;
use App\Stats\Kills\KillsPerHitGroupStat;
use App\Stats\Kills\TotalKillsStat;

class HeadshotPercentageStat extends Stat
{
	protected $dependencies = [TotalKillsStat::class, KillsPerHitGroupStat::class];
	protected $name = 'hs-percentage';

	function compute()
	{
		/** @var TotalKillsStat $totalKillsStat */
		$totalKillsStat = $this->getStat(TotalKillsStat::class);
		/** @var KillsPerHitGroupStat $killsPerGroupStat */
		$killsPerGroupStat = $this->getStat(KillsPerHitGroupStat::class);

		$kills = $totalKillsStat->getValue();
		$hs = $killsPerGroupStat->getValue()['head'] ?? 0;

		dd($hs);

		if ($kills > 0)
			return $hs / $kills * 100;
		else
			return 0;
	}
}