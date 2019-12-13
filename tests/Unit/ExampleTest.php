<?php

namespace Tests\Unit;

use App\Classes\SessionManager;
use App\Server;
use App\Session;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class ExampleTest extends TestCase
{
	//	use RefreshDatabase;

	protected $connectData = [
		'server'        => '177.54.150.15:27001',
		'type'          => 'PLAYER_CONNECTED',
		'date'          => '12/12/2019',
		'time'          => '01:46:44',
		'playerName'    => 'NicKoV',
		'playerId'      => '235',
		'playerSteamId' => 'STEAM_0:0:12345',
		'playerTeam'    => '',
		'address'       => '',
	];

	protected $damageData = [
		'server'        => '177.54.150.15:27001',
		'type'          => 'PLAYER_DAMAGE',
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

	public function test_session_manager_will_create_session_on_player_connected_event()
	{
		$server = Server::firstOrCreate(['address' => '177.54.150.15:27001']);

		$manager = new SessionManager('STEAM_0:0:12345', $server);

		$manager->handleEvent($this->connectData);

		$this->assertDatabaseHas('sessions', [
			'steamid'   => 'STEAM_0:0:12345',
			'server_id' => $server->id,
		]);
	}

	public function test_session_manager_will_reuse_active_session()
	{
		$server = Server::firstOrCreate(['address' => '177.54.150.15:27001']);

		$manager1 = new SessionManager('STEAM_0:0:12345', $server);

		$manager1->handleEvent($this->connectData);

		$manager2 = new SessionManager('STEAM_0:0:12345', $server);

		$this->assertFalse($manager2->getSession()->wasRecentlyCreated);
	}

	public function test_session_manager_will_ignore_events_that_are_not_for_handled_player()
	{
		$server = Server::firstOrCreate(['address' => '177.54.150.15:27001']);

		$manager = new SessionManager('STEAM_0:0:54321', $server);

		$manager->handleEvent($this->connectData);

		$this->assertDatabaseMissing('sessions', [
			'steamid'   => 'STEAM_0:0:12345',
			'server_id' => $server->id,
		]);

		$this->assertDatabaseMissing('sessions', [
			'steamid'   => 'STEAM_0:0:54321',
			'server_id' => $server->id,
		]);
	}

	public function test_session_manager_will_handle_event_correctly()
	{
		$server = Server::firstOrCreate(['address' => '177.54.150.15:27001']);

		$manager = new SessionManager('STEAM_0:0:12345', $server);

		$manager->handleEvent($this->connectData);

		$this->assertNotNull($manager->getSession());

		$manager->handleEvent($this->damageData);
	}
}
