<?php

namespace App\Stats\Hits;

use App\Stats\GroupedHitsStat;

class HitsPerHitGroupStat extends GroupedHitsStat
{
	protected $name = 'hits-by-hitgroup';

	protected function getRelevantKey($weapon, $hitgroup)
	{
		return $hitgroup;
	}
}