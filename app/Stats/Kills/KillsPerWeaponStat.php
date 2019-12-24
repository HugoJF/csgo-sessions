<?php

namespace App\Stats\Kills;

use App\Stats\GroupedKillsStat;

class KillsPerWeaponStat extends GroupedKillsStat
{
	protected $name = 'kills-by-weapon';

	protected function getRelevantKey($weapon, $hitgroup)
	{
		return $weapon;
	}
}