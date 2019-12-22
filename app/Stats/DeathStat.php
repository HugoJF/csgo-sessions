<?php

namespace App\Stats;

use App\Classes\Stat;

class DeathStat extends Stat
{
	protected $name = 'deaths';

	function compute()
	{
		return 10;
	}
}