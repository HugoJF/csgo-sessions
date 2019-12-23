<?php

namespace App\Classes;

use App\Services\ServerService;
use App\Session;
use Exception;

class SessionBuilder
{
	protected $session;

	protected $data = [];
	protected $stats = [];

	/** @var ServerService */
	protected $serverService;

	public function __construct(Session $session)
	{
		$this->session = $session;
		$this->serverService = app(ServerService::class);

		$this->loadStats();
		$this->cacheSessionData();
		// TODO: return array not collection
		$stats = $this->serverService->getStats()->toArray();
		$this->initStats($stats);
	}

	public function getSession()
	{
		return $this->session;
	}

	protected function cacheSessionData()
	{
		$this->data = json_decode($this->session->metrics, true);
	}

	protected function loadStats()
	{
		$this->stats = $this->serverService->getStats()->mapWithKeys(function ($stat) {
			return [$stat => new $stat($this)];
		});
	}

	protected function initStats(array $stats)
	{
		foreach ($stats as $statClass) {
			/** @var Stat $stat */
			$stat = $this->getStat($statClass);

			if (!$stat)
				throw new Exception("Stat $statClass is not loaded in this session.");

			$dependencies = $stat->getDependencies();

			$this->initStats($dependencies);
			$stat->init($this->data);
		}
	}

	public function getStat(string $class)
	{
		return $this->stats[ $class ] ?? null;
	}

	public function toArray()
	{
		return collect($this->stats)->mapWithKeys(function (Stat $stat) {
			return [$stat->getName() => $stat->getValue()];
		});
	}
}