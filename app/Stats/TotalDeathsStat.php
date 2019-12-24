<?php

namespace App\Stats;

class TotalDeathsStat extends BaseSegmentedStat
{
	protected $name = 'deaths-total';
	
	function computeStat($weapon, $type, $hitgroup, $value)
	{
		if ($type !== 'deaths')
			return;
		if (!is_numeric($this->cache))
			$this->cache = 0;

		$this->cache += 1;
	}
}