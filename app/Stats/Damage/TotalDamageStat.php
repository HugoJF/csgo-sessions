<?php

namespace App\Stats\Damage;

use App\Stats\BaseSegmentedStat;

class TotalDamageStat extends BaseSegmentedStat
{
	protected $name = 'damage-total';

	function computeStat($weapon, $type, $hitgroup, $value)
	{
		if ($type !== 'damage')
			return;
		if (!is_numeric($this->cache))
			$this->cache = 0;

		$this->cache += $value;
	}
}