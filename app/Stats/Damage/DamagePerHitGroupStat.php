<?php

namespace App\Stats\Damage;

use App\Stats\GroupedDamageStat;

class DamagePerHitGroupStat extends GroupedDamageStat
{
	protected $name = 'damage-by-hitgroup';

	protected function getRelevantKey($weapon, $hitgroup)
	{
		return $hitgroup;
	}
}