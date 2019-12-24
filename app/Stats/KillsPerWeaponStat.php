<?php

namespace App\Stats;

class KillsPerWeaponStat extends GroupedKillsStat
{
	protected $name = 'kills-by-weapon';

	protected function getRelevantKey($weapon, $hitgroup)
	{
		return $weapon;
	}
}