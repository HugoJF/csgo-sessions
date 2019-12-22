<?php

namespace Tests\Unit;

use App\Exceptions\MultipleSessionsException;
use App\Exceptions\UntrackedServerException;
use App\Server;
use App\Services\SessionService;
use App\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class SessionServiceTest extends TestCase
{
	use RefreshDatabase;

	/** @var SessionService */
	protected $service;
	protected $genericSteamid = 'STEAM_0:0:12345';
	protected $genericSteamid2 = 'STEAM_0:0:54321';

	protected function setUp(): void
	{
		parent::setUp();

		$this->service = app(SessionService::class);
	}

	public function test_close_active_session_will_throw_exception_on_multiple_active_sessions()
	{
		Session::unguard();
		Server::unguard();

		$this->expectException(MultipleSessionsException::class);

		$server = factory(Server::class)->create();
		factory(Session::class, 2)->state('active')->create([
			'steamid'   => $this->genericSteamid,
			'server_id' => $server->id,
		]);

		$this->service->closeActiveSessions($this->genericSteamid, true);
	}

	public function test_close_active_sessions()
	{
		$server = factory(Server::class)->create();
		$session = factory(Session::class)->state('active')->create([
			'steamid'   => $this->genericSteamid,
			'server_id' => $server->id,
		]);

		$this->service->closeActiveSessions($this->genericSteamid);

		$this->assertDatabaseHas('sessions', [
			'id'        => $session->id,
			'steamid'   => $this->genericSteamid,
			'server_id' => $server->id,
			'active'    => false,
		]);
	}

	public function test_get_active_sessions()
	{
		$server = factory(Server::class)->create();
		$session = factory(Session::class)->state('active')->create([
			'steamid'   => $this->genericSteamid,
			'server_id' => $server->id,
		]);
		factory(Session::class)->state('inactive')->create();

		$this->service->closeActiveSessions($this->genericSteamid);

		$this->assertDatabaseHas('sessions', [
			'id'        => $session->id,
			'steamid'   => $this->genericSteamid,
			'server_id' => $server->id,
			'active'    => false,
		]);
	}

	public function test_get_active_session_managers_by_server()
	{
		$server1 = factory(Server::class)->create(['address' => '1.1.1.1:1']);
		$server2 = factory(Server::class)->create(['address' => '2.2.2.2:2']);

		factory(Session::class)->state('active')->create([
			'steamid'   => $this->genericSteamid,
			'server_id' => $server1->id,
		]);
		factory(Session::class)->state('active')->create([
			'steamid'   => $this->genericSteamid2,
			'server_id' => $server2->id,
		]);

		$managersByServer = $this->service->getActiveSessionManagersByServer();

		$this->assertArrayHasKey('1.1.1.1:1', $managersByServer);
		$this->assertArrayHasKey('2.2.2.2:2', $managersByServer);
	}

	public function test_session_create_will_throw_exception_on_servers_that_are_not_in_the_database()
	{
		$this->expectException(UntrackedServerException::class);

		$this->service->create($this->genericSteamid, '1.1.1.1:1');
	}

	public function test_session_create()
	{
		$server = factory(Server::class)->create();

		$this->service->create($this->genericSteamid, $server->address);

		$this->assertDatabaseHas('sessions', [
			'active'    => true,
			'server_id' => $server->id,
			'steamid'   => $this->genericSteamid,
		]);
	}

	public function test_session_close_will_build_data()
	{
		Redis::command('flushall');

		$server = factory(Server::class)->create();

		$session = factory(Session::class)->state('active')->create(['server_id' => $server->id]);

		$data = [
			'a' => 10,
			'b' => 20,
			'c' => 30,
		];

		foreach ($data as $key => $value) {
			Redis::set("$session->id.$key", $value);
		}

		$this->service->closeActiveSessions($session->steamid);

		$session = Session::find($session->id);

		$metrics = json_decode($session->metrics, true);

		$this->assertEquals($data, $metrics);
	}
}
