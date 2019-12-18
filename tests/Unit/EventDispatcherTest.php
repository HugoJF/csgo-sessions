<?php

namespace Tests\Unit;

use App\Classes\EventDispatcher;
use App\Classes\SessionManager;
use App\Server;
use App\Services\SessionService;
use App\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class EventDispatcherTest extends TestCase
{
	use MockeryPHPUnitIntegration;
	use RefreshDatabase;

	protected $connectData = [
		'server'        => '177.54.150.15:27001',
		'type'          => 'PlayerConnected',
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

	protected $disconnectData = [
		"date"          => "12/15/2019",
		"time"          => "03:12:00",
		"playerName"    => "toddynho",
		"playerId"      => "366",
		"playerSteamId" => "STEAM_0:0:12345",
		"playerTeam"    => "CT",
		"reason"        => "PlayerDisconnect",
		"server"        => "177.54.150.15:27001",
		"type"          => "PlayerDisconnected"
	];

	protected function setUp(): void
	{
		parent::setUp();

		factory(Server::class)->create(['address' => '177.54.150.15:27001']);
	}

	public function test_generic_event_will_be_passed_to_every_active_session_manager()
	{
		// Check if PLAYER_DAMAGE will cause one SessionManager to have it's handleEvent called
		$mockedManager = Mockery::mock(SessionManager::class)->makePartial();
		$mockedManager->shouldReceive('handleEvent')->once();

		// Mock SessionService to avoid going to database to retrieve active sessions
		// and return the mocked instance instead
		$sessionService = Mockery::mock(SessionService::class)->makePartial();
		$sessionService->shouldReceive('getActiveSessionManagersByServer')
					   ->once()
					   ->andReturn([
						   $this->damageData['server'] => [$mockedManager],
					   ]);

		app()->instance(SessionService::class, $sessionService);

		$dispatcher = app(EventDispatcher::class);
		$dispatcher->dispatchEvent($this->damageData);
	}

	public function test_connect_event_will_cause_a_new_session_to_be_created()
	{
		// Mock SessionService to avoid looking at database for active sessions (assume no sessions are active)
		// and to avoid actually creating the session (the creation itself should be tested in SessionServiceTest)
		$sessionService = Mockery::mock(SessionService::class)->makePartial();
		$sessionService->shouldReceive('getActiveSessionManagersByServer')
					   ->once()
					   ->andReturn([]);
		$sessionService->shouldReceive('create')
					   ->withArgs([$this->connectData['playerSteamId'], $this->connectData['server']])
					   ->once()
					   ->andReturn(Session::make());

		app()->instance(SessionService::class, $sessionService);

		$dispatcher = app(EventDispatcher::class);
		$dispatcher->dispatchEvent($this->connectData);
	}

	public function test_disconnect_event_will_trigger_session_closing()
	{
		// Check if SessionService called closeActiveSession when receiving a PLAYER_DISCONNECT event
		$sessionService = Mockery::mock(SessionService::class);
		$sessionService->shouldReceive('getActiveSessionManagersByServer')
					   ->once()
					   ->andReturn([]);
		$sessionService->shouldReceive('closeActiveSession')
					   ->withArgs([$this->connectData['playerSteamId']])
					   ->once();

		app()->instance(SessionService::class, $sessionService);

		$dispatcher = app(EventDispatcher::class);
		$dispatcher->dispatchEvent($this->disconnectData);
	}
}
