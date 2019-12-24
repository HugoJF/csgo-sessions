<?php

namespace App\Stats\Hits;

use App\Stats\BaseTotalStat;

class TotalHitsStat extends BaseTotalStat
{
	protected $name = 'hits-total';

	protected function getMetricType()
	{
		return 'hits';
	}
}