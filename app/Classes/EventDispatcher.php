<?php

namespace App\Classes;

use App\Server;
use App\Services\EventService;
use App\Services\SessionService;
use Exception;
use Illuminate\Support\Facades\Redis;

class EventDispatcher
{
	protected $sessionService;
	protected $eventService;

	protected $sessions;

	public function __construct(SessionService $sessionService, EventService $eventService)
	{
		$this->sessionService = $sessionService;
		$this->eventService = $eventService;

		$this->loadActiveSessions();
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
		if ($this->eventService->isConnectEvent($event))
			$this->handleConnect($event);
		else if ($this->eventService->isDisconnectEvent($event))
			$this->handleDisconnect($event);
		else
			$this->handleEvent($event);
	}

	protected function handleEvent($event)
	{
		$server = $event['server'] ?? null;
		if (!$server)
			throw new Exception('Event did not pass server address');

		// TODO: ew
		$tracked = Server::where('address', $server)->exists();
		if (!$tracked)
			return;

		$serverSessions = $this->sessions[ $server ] ?? [];

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

		$this->sessionService->closeActiveSession($steamid);
	}

	protected function handleConnect(array $event): void
	{
		$steamid = $event['playerSteamId'] ?? null;
		$server = $event['server'] ?? null;
		if (!$steamid)
			throw new Exception('PlayerConnect event did not pass SteamID');

		if (!$server)
			throw new Exception('PlayerConnect event did not pass server address');

		$session = $this->sessionService->create($steamid, $server);
		if (!array_key_exists($server, $this->sessions))
			$this->sessions[ $server ] = [];
		$this->sessions[ $server ][] = new SessionManager($session);
	}
}