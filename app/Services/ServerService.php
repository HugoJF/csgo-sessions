<?php

namespace App\Services;

use App\Collectors\DamageTotalCollector;

class ServerService
{
	public function getCollectors()
	{
		// TODO: implement
		return collect([DamageTotalCollector::class]);
	}
}