<?php

namespace App\Stats;

abstract class GroupedHitsStat extends BaseGroupedStat
{
	protected function getMetricType()
	{
		return 'kills';
	}
}