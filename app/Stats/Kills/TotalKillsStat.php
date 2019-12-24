<?php

namespace App\Stats\Kills;

use App\Stats\Damage\BaseTotalStat;

class TotalKillsStat extends BaseTotalStat
{
	protected $name = 'kills-total';

	protected function getMetricType()
	{
		return 'kills';
	}
}