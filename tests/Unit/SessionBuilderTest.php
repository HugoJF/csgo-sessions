<?php

namespace Tests\Unit;

use App\Classes\EventDispatcher;
use App\Classes\SessionBuilder;
use App\Server;
use App\Services\SessionService;
use App\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class SessionBuilderTest extends TestCase
{
	use RefreshDatabase;

	protected $damageData = [
		'server'        => '177.54.150.15:27001',
		'type'          => 'PlayerDamage',
		'date'          => '12/12/2019',
		'time'          => '01:46:44',
		'attackerName'  => 'gael',
		'attackerId'    => '228',
		'attackerSteam' => 'STEAM_0:0:12345',
		'attackerTeam'  => 'CT',
		'targetName'    => 'NeSt - MjKe',
		'targetId'      => '227',
		'targetSteam'   => 'STEAM_0:0:54321',
		'targetTeam'    => 'TERRORIST',
		'weapon'        => 'deagle',
		'damage'        => '148',
		'armorDamage'   => '0',
		'targetHealth'  => '0',
		'targetArmor'   => '100',
		'hitgroup'      => 'head',
	];

	public function test_stat_dependencies_are_solved()
	{
		Server::unguard();
		Redis::command('flushall');
		$server = Server::create(['address' => '177.54.150.15:27001']);
		$session = factory(Session::class)->state('active')->create([
			'steamid'   => 'STEAM_0:0:12345',
			'server_id' => $server->id,
		]);

		/** @var EventDispatcher $dispatcher */
		$dispatcher = app(EventDispatcher::class);
		$dispatcher->dispatchEvent($this->damageData);

		/** @var SessionService $service */
		$service = app(SessionService::class);
		$service->closeActiveSessions($session->steamid);


		$builder = new SessionBuilder($session->refresh());

		dd($builder->toArray());
	}
}
