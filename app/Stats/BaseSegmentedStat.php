<?php

namespace App\Stats;

use App\Classes\Stat;

abstract class BaseSegmentedStat extends Stat
{
	protected $pattern = '/^([A-Za-z0-9_]+)\.([A-Za-z0-9_]+)\.([A-Za-z0-9_]+)$/';
	protected $cache = [];

	abstract protected function computeStat($weapon, $type, $hitgroup, $value);

	protected function compute()
	{
		foreach ($this->data as $key => $value) {
			if (preg_match($this->pattern, $key, $matches)) {
				list($match, $type, $weapon, $hitgroup) = $matches;

				$this->computeStat($weapon, $type, $hitgroup, $value);
			}
		}

		// TODO: cache should be private
		return $this->cache;
	}
}