<?php

namespace App\Stats;

abstract class BaseTotalStat extends BaseSegmentedStat
{
	protected $name = 'damage-total';

	protected $cache = 0;

	abstract protected function getMetricType();

	function computeStat($weapon, $type, $hitgroup, $value)
	{
		if ($type !== $this->getMetricType())
			return;

		if (!is_numeric($this->cache))
			$this->cache = 0;

		$this->cache += $value;
	}
}