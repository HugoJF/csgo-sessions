<?php

namespace App\Stats\Damage;

class TotalDamageStat extends BaseTotalStat
{
	protected $name = 'damage-total';

	protected function getMetricType()
	{
		return 'damage';
	}
}