<?php

namespace App\Stats;

use App\Classes\Stat;

class KillStat extends Stat
{
	protected $name = 'kills';

	function compute()
	{
		return 40;
	}
}