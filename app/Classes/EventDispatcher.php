<?php

namespace App\Classes;

use App\Exceptions\MissingEventDataException;
use App\Server;
use App\Services\EventService;
use App\Services\ServerService;
use App\Services\SessionService;
use Exception;
use Illuminate\Support\Facades\Redis;

class EventDispatcher
{
	protected $sessionService;
	protected $eventService;
	protected $serverService;

	public $sessions;
	private $servers;

	public function __construct(SessionService $sessionService, EventService $eventService, ServerService $serverService)
	{
		$this->sessionService = $sessionService;
		$this->eventService = $eventService;
		$this->serverService = $serverService;

		$this->loadActiveSessions();
		$this->loadTrackedServers();
	}

	private function loadTrackedServers()
	{
		$this->servers = Server::all()->mapWithKeys(function (Server $server) {
			return [$server->address => $server];
		});
	}

	/**
	 * Load active sessions grouped by server address
	 */
	protected function loadActiveSessions()
	{
		$this->sessions = $this->sessionService->getActiveSessionManagersByServer();
	}

	public function dispatchEvent($event)
	{
		$address = $event['server'];

		if (!array_key_exists($address, $this->servers)) {
			info("Received event for server $address that is not being tracked", compact('event'));

			return;
		}

		if ($this->eventService->isConnectEvent($event))
			$this->handleConnect($event);
		else if ($this->eventService->isDisconnectEvent($event))
			$this->handleDisconnect($event);
		else
			$this->handleEvent($event);
	}

	protected function handleEvent($event)
	{
		if (!array_key_exists('server', $event))
			throw new MissingEventDataException('server');

		$address = $event['server'];

		$serverSessions = $this->sessions[ $address ] ?? [];

		/** @var SessionManager $session */
		foreach ($serverSessions as $session) {
			$session->handleEvent($event);
		}
	}

	protected function handleDisconnect(array $event): void
	{
		$steamid = $event['playerSteamId'] ?? null;
		if (!$steamid)
			throw new Exception("PlayerDisconnect event did not pass SteamID");

		$this->sessionService->closeActiveSessions(steamid2($steamid));
	}

	protected function handleConnect(array $event): void
	{
		$steamid = $event['playerSteamId'] ?? null;
		$server = $event['server'] ?? null;
		if (!$steamid)
			throw new Exception('PlayerConnect event did not pass SteamID');

		if (!$server)
			throw new Exception('PlayerConnect event did not pass server address');

		$session = $this->sessionService->create(steamid2($steamid), $server);
		if (!array_key_exists($server, $this->sessions))
			$this->sessions[ $server ] = [];
		$this->sessions[ $server ][] = new SessionManager($session);
	}
}