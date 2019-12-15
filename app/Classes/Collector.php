<?php

namespace App\Classes;

use Exception;
use Illuminate\Support\Facades\Redis;

abstract class Collector
{
	/**
	 * @var SessionManager
	 */
	protected $session;

	protected $expects = [];
	protected $acceptedEvents = [];

	public function __construct(SessionManager $session)
	{
		$this->session = $session;
	}

	public function command(string $name, string $command, array $data)
	{
		$key = $this->session->getRedisKey($name);

		return Redis::command($command, array_merge([$key], $data));
	}

	public function accepts(array $event): bool
	{
		// TODO: log
		if (!array_key_exists('type', $event))
			return false;

		return in_array($event['type'], $this->acceptedEvents);
	}

	protected function checkExpectedData(array $event): void
	{
		foreach ($this->expects as $expect) {
			if (!array_key_exists($expect, $event)) {
				// TODO: create exception for this to allow event serialization
				throw new Exception("Expected key `$expect` does not exist in event.");
			}
		}
	}

	public function receiveEvent(array $event): void
	{
		$this->checkExpectedData($event);
		$this->collect($event);
	}

	abstract public function collect(array $event): void;
}