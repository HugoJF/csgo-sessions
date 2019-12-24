<?php

namespace App\Stats;

abstract class GroupedKillsStat extends BaseSegmentedStat
{
	abstract protected function getRelevantKey($weapon, $hitgroup);

	function computeStat($weapon, $type, $hitgroup, $value)
	{
		if ($type !== 'kills')
			return;

		$key = $this->getRelevantKey($weapon, $hitgroup);
		if (!array_key_exists($key, $this->cache))
			$this->cache[ $key ] = 0;

		$this->cache[ $key ] += $value;
	}
}