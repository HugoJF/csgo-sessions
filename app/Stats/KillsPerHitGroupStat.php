<?php

namespace App\Stats;

class KillsPerHitGroupStat extends GroupedKillsStat
{
	protected $name = 'kills-by-hitgroup';

	protected function getRelevantKey($weapon, $hitgroup)
	{
		return $hitgroup;
	}
}