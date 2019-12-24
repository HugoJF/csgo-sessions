<?php

namespace App\Stats;

class TotalKillsStat extends BaseSegmentedStat
{
	protected $name = 'kills-total';
	
	function computeStat($weapon, $type, $hitgroup, $value)
	{
		if ($type !== 'kills')
			return;
		if (!is_numeric($this->cache))
			$this->cache = 0;

		$this->cache += $value;
	}
}