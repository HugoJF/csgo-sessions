<?php

namespace App\Stats;

class DamagePerHitGroupStat extends GroupedDamageStat
{
	protected $name = 'damage-by-hitgroup';

	protected function getRelevantKey($weapon, $hitgroup)
	{
		return $hitgroup;
	}
}