<?php

namespace App\Stats;

class HitsPerWeaponStat extends GroupedDamageStat
{
	protected $name = 'hits-by-weapon';

	protected function getRelevantKey($weapon, $hitgroup)
	{
		return $weapon;
	}
}