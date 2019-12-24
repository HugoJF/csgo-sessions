<?php

namespace App\Stats\Deaths;

use App\Stats\BaseTotalStat;

class TotalDeathsStat extends BaseTotalStat
{
	protected $name = 'deaths-total';

	protected function getMetricType()
	{
		return 'deaths';
	}

	function computeStat($weapon, $type, $hitgroup, $value)
	{
		if ($type !== 'deaths')
			return;
		if (!is_numeric($this->cache))
			$this->cache = 0;

		$this->cache += $value;
	}
}