<?php

namespace Tests\Feature;

use App\Classes\EventDispatcher;
use App\Server;
use App\Session;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class EventTest extends TestCase
{
	use RefreshDatabase;

	protected $connectData = [
		'server'        => '177.54.150.15:27001',
		'type'          => 'PlayerConnected',
		'date'          => '12/12/2019',
		'time'          => '01:46:44',
		'playerName'    => 'NicKoV',
		'playerId'      => '235',
		'playerSteamId' => 'STEAM_1_1:11111',
		'playerTeam'    => '',
		'address'       => '',
	];

	protected $damageData = [
		'server'        => '177.54.150.15:27001',
		'type'          => 'PlayerDamage',
		'date'          => '12/12/2019',
		'time'          => '01:46:44',
		'attackerName'  => 'gael',
		'attackerId'    => '228',
		'attackerSteam' => 'STEAM_1_1:11111',
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

	protected $disconnectData = [
		"date"          => "12/15/2019",
		"time"          => "03:12:00",
		"playerName"    => "toddynho",
		"playerId"      => "366",
		"playerSteamId" => "STEAM_1_1:11111",
		"playerTeam"    => "CT",
		"reason"        => "PlayerDisconnect",
		"server"        => "177.54.150.15:27001",
		"type"          => "PlayerDisconnected",
	];

	protected $steamid = 'STEAM_1_1:11111';

	public function not_test_connect_damage_and_disconnect_events_will_generate_session_parameters()
	{
		$server = factory(Server::class)->create(['address' => '177.54.150.15:27001']);

		/** @var EventDispatcher $dispatcher */
		$dispatcher = app(EventDispatcher::class);
		$dispatcher->dispatchEvent($this->connectData);
		$dispatcher->dispatchEvent($this->damageData);
		$dispatcher->dispatchEvent($this->disconnectData);
	}

	public function test_event_dispatcher_can_resume_sessions()
	{
		$server = factory(Server::class)->create(['address' => '177.54.150.15:27001']);

		/** @var EventDispatcher $dispatcher1 */
		$dispatcher1 = app(EventDispatcher::class);
		$dispatcher1->dispatchEvent($this->connectData);
		$dispatcher1->dispatchEvent($this->damageData);

		/** @var EventDispatcher $dispatcher2 */
		$dispatcher2 = app(EventDispatcher::class);
		$dispatcher2->dispatchEvent($this->damageData);
		$dispatcher2->dispatchEvent($this->disconnectData);

//		dd(Session::all()->toArray());

		$keys = Redis::keys('*');
		$data = collect($keys)->mapWithKeys(function ($key) {
			$value = Redis::connection('input')->get($key);

			return [$key => $value];
		});

		dd($data);
	}
}
