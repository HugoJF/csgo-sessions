<?php

namespace App\Stats\Damage;

use App\Stats\GroupedDamageStat;

class DamagePerWeaponStat extends GroupedDamageStat
{
	protected $name = 'damage-by-weapon';

	protected function getRelevantKey($weapon, $hitgroup)
	{
		return $weapon;
	}
}