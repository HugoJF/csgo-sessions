<?php

namespace App\Stats;

abstract class GroupedDamageStat extends BaseSegmentedStat
{
	abstract protected function getRelevantKey($weapon, $hitgroup);

	function computeStat($weapon, $type, $hitgroup, $value)
	{
		if ($type !== 'damage')
			return;

		$key = $this->getRelevantKey($weapon, $hitgroup);
		if (!array_key_exists($key, $this->cache))
			$this->cache[ $key ] = 0;

		$this->cache[ $key ] += $value;
	}
}