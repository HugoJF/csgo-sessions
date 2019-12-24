<?php

namespace App\Stats;

abstract class GroupedDamageStat extends BaseGroupedStat
{
	protected function getMetricType()
	{
		return 'damage';
	}
}