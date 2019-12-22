<?php

namespace App\Stats;

class HitsPerHitGroupStat extends GroupedDamageStat
{
	protected $name = 'hits-by-hitgroup';

	protected function getRelevantKey($weapon, $hitgroup)
	{
		return $hitgroup;
	}
}