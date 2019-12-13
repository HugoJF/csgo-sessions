<?php

namespace App\Classes;

use App\Server;
use App\Services\ServerService;
use App\Services\SessionService;
use App\Services\UserService;
use Illuminate\Support\Collection;

class SessionManager
{
	protected $steamid;
	protected $server;
	protected $session;

	protected $collectors;

	public function __construct($steamid, Server $server)
	{
		$this->steamid = $steamid;
		$this->server = $server;

		$this->findSession($steamid, $server);
		$this->loadCollectors();
	}

	public function getRedisPrefix()
	{
		return $this->session->id;
	}

	public function getRedisKey(string $name)
	{
		$prefix = $this->getRedisPrefix();

		return "$prefix.$name";
	}

	private function findSession($steamid, Server $server): void
	{
		/** @var UserService $userService */
		$userService = app(UserService::class);

		$this->session = $userService->findActiveSession($server, $steamid);
	}

	private function loadCollectors()
	{
		if (!$this->session)
			return;

		/** @var ServerService $service */
		$service = app(ServerService::class);

		/** @var Collection $collectors */
		$collectors = $service->getCollectors($this->server);

		$this->collectors = $collectors->map(function ($collector) {
			return new $collector($this);
		});
	}

	public function handleEvent(array $event)
	{
		// TODO: improve
		// TODO: not always playerSteamId is the same, sometimes it can be called attackerSteamId, somehow fix this shit
		if (($event['playerSteamId'] ?? null) !== $this->steamid)
			return;
		if (($event['server'] ?? null) !== $this->server->address)
			return;

		if ($event['type'] === 'PlayerDisconnected')
			$this->handleDisconnect($event);
		else if ($this->session)
			$this->handleSessionEvent($event);
		else
			$this->handleConnect($event);
	}

	/**
	 * @param array $event
	 */
	protected function handleSessionEvent(array $event): void
	{
		/** @var Collector $collector */
		foreach ($this->collectors as $collector) {
			if ($collector->accepts($event))
				$collector->collect($event);
		}
	}

	protected function handleDisconnect(array $event): void
	{
		$this->session->active = false;
		$this->session->save();
	}

	protected function handleConnect(array $event): void
	{
		$type = $event['type'] ?? false;
		if ($type !== 'PlayerConnected') // TODO:
			return;

		$sessionService = app(SessionService::class);

		$this->session = $sessionService->create($this->steamid, $this->server);
		$this->loadCollectors();
	}

	/**
	 * @return mixed
	 */
	public function getSession()
	{
		return $this->session;
	}
}