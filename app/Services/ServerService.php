<?php

namespace App\Services;

use App\Collectors\DamageReceivedTotalCollector;
use App\Collectors\DamageTotalCollector;
use App\Collectors\DeathsTotalCollector;
use App\Collectors\HitsTotalCollector;
use App\Collectors\KillsTotalCollector;
use App\Server;
use App\Stats\DamagePerHitGroupStat;
use App\Stats\DamagePerWeaponStat;
use App\Stats\DeathStat;
use App\Stats\HitsPerHitGroupStat;
use App\Stats\HitsPerWeaponStat;
use App\Stats\KdrStat;
use App\Stats\KillStat;
use App\Stats\TotalDamageStat;
use App\Stats\TotalHitsStat;

class ServerService
{
	// TODO: must be by server
	public function getCollectors()
	{
		// TODO: implement
		return collect([
			DamageTotalCollector::class,
			DamageReceivedTotalCollector::class,
			HitsTotalCollector::class,
			DeathsTotalCollector::class,
			KillsTotalCollector::class,
			// total armor damage done
			// total armor damage received
			// hits received
		]);
	}

	// TODO: must be by server
	public function getStats()
	{
		return collect([
			KillStat::class,
			DeathStat::class,
			KdrStat::class,

			DamagePerHitGroupStat::class,
			DamagePerWeaponStat::class,
			TotalDamageStat::class,

			HitsPerHitGroupStat::class,
			HitsPerWeaponStat::class,
			TotalHitsStat::class,
		]);
	}

	public function findServerByAddress($address)
	{
		return Server::where('address', $address)->first();
	}
}