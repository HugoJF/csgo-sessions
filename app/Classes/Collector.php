<?php

namespace App\Classes;

use App\Exceptions\MissingEventDataException;
use Exception;
use Illuminate\Support\Facades\Redis;

abstract class Collector
{
	/**
	 * @var SessionManager
	 */
	protected $manager;

	protected $expects = [];
	protected $acceptedEvents = [];
	protected $eventOwnerKey = [];

	public function __construct(SessionManager $session)
	{
		$this->manager = $session;
	}

	public function getCleanKey($string)
	{
		return preg_replace('/[^A-Za-z0-9_\.]/', '_', $string);
	}

	public function command(string $command, string $name, array $data)
	{
		$key = $this->manager->getRedisKey($name);

		return Redis::command($command, array_merge([$key], $data));
	}

	public function accepts(array $event): bool
	{
		return $this->isCompatibleWith($event) && $this->matchesEventOwner($event);
	}

	protected function checkExpectedData(array $event): void
	{
		foreach ($this->expects as $expect) {
			if (!array_key_exists($expect, $event)) {
				throw new MissingEventDataException($expect);
			}
		}
	}

	public function receiveEvent(array $event): void
	{
		$this->checkExpectedData($event);
		$this->collect($event);
	}

	/**
	 * @param array $event
	 *
	 * @return bool
	 * @throws MissingEventDataException
	 */
	protected function isCompatibleWith(array $event): bool
	{
		if (!array_key_exists('type', $event)) {
			throw new MissingEventDataException('type');
		}

		return in_array($event['type'], $this->acceptedEvents);
	}

	protected function matchesEventOwner(array $event)
	{
		// If event owner key array is not defined, there's nothing to check
		if (count($this->eventOwnerKey) === 0)
			return true;

		$type = $event['type'];

		// Pretty error for missing event owner key
		if (!array_key_exists($type, $this->eventOwnerKey)) {
			$class = static::class;
			throw new Exception("Missing event owner key for event type $type on collector $class");
		}

		$ownerKey = $this->eventOwnerKey[ $type ];

		return $event[ $ownerKey ] === $this->manager->getSession()->steamid;
	}

	abstract public function collect(array $event): void;

}