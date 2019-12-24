<?php

namespace App\Stats\Hits;

use App\Stats\GroupedHitsStat;

class HitsPerWeaponStat extends GroupedHitsStat
{
	protected $name = 'hits-by-weapon';

	protected function getRelevantKey($weapon, $hitgroup)
	{
		return $weapon;
	}
}