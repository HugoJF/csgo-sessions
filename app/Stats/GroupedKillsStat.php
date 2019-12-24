<?php

namespace App\Stats;

abstract class GroupedKillsStat extends BaseGroupedStat
{
	protected function getMetricType()
	{
		return 'kills';
	}
}