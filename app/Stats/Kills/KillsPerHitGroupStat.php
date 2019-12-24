<?php

namespace App\Stats\Kills;

use App\Stats\GroupedKillsStat;

class KillsPerHitGroupStat extends GroupedKillsStat
{
	protected $name = 'kills-by-hitgroup';

	protected function getRelevantKey($weapon, $hitgroup)
	{
		return $hitgroup;
	}
}