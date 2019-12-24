<?php

namespace App\Stats\Damage;

use App\Stats\BaseTotalStat;

class TotalDamageStat extends BaseTotalStat
{
	protected $name = 'damage-total';

	protected function getMetricType()
	{
		return 'damage';
	}
}