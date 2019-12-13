<?php

namespace App\Services;

use App\Server;
use App\Session;
use Illuminate\Support\Collection;

class UserService
{
	public function endSessions($sessions)
	{
		foreach ($sessions as $session) {
			$this->endSession($session);
		}
	}

	public function endSession(Session $session)
	{
		$session->active = false;
		$session->save();
	}

	public function findActiveSession(Server $server, $steamid)
	{
		/** @var Collection $sessions */
		$sessions = $server->sessions()
						   ->where('steamid', $steamid)
						   ->where('active', true)
						   ->get();

		// End every session but the newest
		if ($sessions->count() > 1) {
			$sorted = $sessions->sortBy('created_at');
			$sessions = collect($sorted->pop());
			$this->endSessions($sorted);
		}

		return $sessions->first();
	}
}