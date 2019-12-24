<?php

namespace App\Stats;

abstract class BaseGroupedStat extends BaseSegmentedStat
{
	abstract protected function getRelevantKey($weapon, $hitgroup);

	abstract protected function getMetricType();

	protected function computeStat($weapon, $type, $hitgroup, $value)
	{
		if ($type !== $this->getMetricType())
			return;

		$key = $this->getRelevantKey($weapon, $hitgroup);
		if (!array_key_exists($key, $this->cache))
			$this->cache[ $key ] = 0;

		$this->cache[ $key ] += $value;
	}
}