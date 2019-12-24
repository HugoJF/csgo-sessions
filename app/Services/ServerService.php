<?php

namespace App\Services;

use App\Collectors\DamageReceivedTotalCollector;
use App\Collectors\DamageTotalCollector;
use App\Collectors\DeathsTotalCollector;
use App\Collectors\HitsTotalCollector;
use App\Collectors\KillsTotalCollector;
use App\Server;
use App\Stats\Damage\DamagePerHitGroupStat;
use App\Stats\Damage\DamagePerWeaponStat;
use App\Stats\DeathStat;
use App\Stats\Misc\HeadshotPercentageStat;
use App\Stats\Hits\HitsPerHitGroupStat;
use App\Stats\Hits\HitsPerWeaponStat;
use App\Stats\Misc\KdrStat;
use App\Stats\Kills\KillsPerHitGroupStat;
use App\Stats\Kills\KillsPerWeaponStat;
use App\Stats\KillStat;
use App\Stats\Damage\TotalDamageStat;
use App\Stats\Deaths\TotalDeathsStat;
use App\Stats\Hits\TotalHitsStat;
use App\Stats\Kills\TotalKillsStat;

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
			DamagePerHitGroupStat::class,
			DamagePerWeaponStat::class,
			TotalDamageStat::class,

			HitsPerHitGroupStat::class,
			HitsPerWeaponStat::class,
			TotalHitsStat::class,

			KillsPerWeaponStat::class,
			KillsPerHitGroupStat::class,
			TotalKillsStat::class,

			TotalDeathsStat::class,

			HeadshotPercentageStat::class,
			KdrStat::class,
		]);
	}

	public function findServerByAddress($address)
	{
		return Server::where('address', $address)->first();
	}
}