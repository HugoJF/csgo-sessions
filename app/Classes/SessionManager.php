<?php

namespace App\Classes;

use App\Services\ServerService;
use App\Session;
use Illuminate\Support\Collection;

class SessionManager
{
	protected $server;
	protected $session;

	protected $collectors;

	public function __construct(Session $session)
	{
		$this->session = $session;
		$this->server = $session->server;

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
		if (($event['server'] ?? null) !== $this->server->address)
			return;

		$this->handleGenericEvent($event);
	}

	/**
	 * @param array $event
	 */
	protected function handleGenericEvent(array $event): void
	{
		/** @var Collector $collector */
		foreach ($this->collectors as $collector) {
			if ($collector->accepts($event))
				$collector->collect($event);
		}
	}

	/**
	 * @return mixed
	 */
	public function getSession()
	{
		return $this->session;
	}
}