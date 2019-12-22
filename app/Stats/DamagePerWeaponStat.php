<?php

namespace App\Stats;

class DamagePerWeaponStat extends GroupedDamageStat
{
	protected $name = 'damage-by-weapon';

	protected function getRelevantKey($weapon, $hitgroup)
	{
		return $weapon;
	}
}