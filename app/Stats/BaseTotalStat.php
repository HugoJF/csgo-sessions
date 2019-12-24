<?php

namespace App\Stats\Damage;

use App\Stats\BaseSegmentedStat;

abstract class BaseTotalStat extends BaseSegmentedStat
{
	protected $name = 'damage-total';

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