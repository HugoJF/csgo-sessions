<?php

namespace App\Stats\Hits;

use App\Stats\Damage\BaseTotalStat;

class TotalHitsStat extends BaseTotalStat
{
	protected $name = 'hits-total';

	protected function getMetricType()
	{
		return 'hits';
	}
}