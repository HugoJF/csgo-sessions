<?php

namespace App\Services;

class EventService
{
	public static function isEventType($event, $type)
	{
		return ($event['type'] ?? null) === $type;
	}

	public function isConnectEvent($event)
	{
		return $this->isEventType($event, 'PlayerConnected');
	}

	public function isDisconnectEvent($event)
	{
		return $this->isEventType($event, 'PlayerDisconnected');
	}
}