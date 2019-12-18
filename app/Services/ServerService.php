<?php

namespace App\Services;

use App\Collectors\DamageTotalCollector;
use App\Server;

class ServerService
{
	public function getCollectors()
	{
		// TODO: implement
		return collect([
			DamageTotalCollector::class,
		]);
	}

	public function findServerByAddress($address)
	{
		return Server::where('address', $address)->first();
	}
}