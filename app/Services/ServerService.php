<?php

namespace App\Services;

use App\Collectors\DamageTotalCollector;
use App\Collectors\HitsTotalCollector;
use App\Server;

class ServerService
{
	public function getCollectors()
	{
		// TODO: implement
		return collect([
			DamageTotalCollector::class,
			HitsTotalCollector::class,
		]);
	}

	public function findServerByAddress($address)
	{
		return Server::where('address', $address)->first();
	}
}