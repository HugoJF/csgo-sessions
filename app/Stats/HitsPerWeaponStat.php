<?php

namespace App\Stats;

class HitsPerWeaponStat extends GroupedHitsStat
{
	protected $name = 'hits-by-weapon';

	protected function getRelevantKey($weapon, $hitgroup)
	{
		return $weapon;
	}
}