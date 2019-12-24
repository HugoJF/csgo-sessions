<?php

namespace App\Stats\Misc;

use App\Classes\Stat;
use App\Session;

class DurationStat extends Stat
{
	protected function compute()
	{
		/** @var Session $session */
		$session = $this->builder->getSession();

		return $session->closed_at->diffInMinutes($session->created_at, true);
	}
}