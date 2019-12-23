<?php

namespace App\Services;

use App\Classes\SessionManager;
use App\Exceptions\MultipleSessionsException;
use App\Exceptions\UntrackedServerException;
use App\Session;
use Exception;
use Illuminate\Support\Facades\Redis;

class SessionService
{
	public function closeActiveSessions($steamid, $strict = false)
	{
		$sessions = Session::where('steamid', $steamid)->where('active', true)->get();

		if ($strict && $sessions->count() > 1)
			throw new MultipleSessionsException($steamid);

		foreach ($sessions as $session) {
			$session->steamid = $steamid;
			$session->active = false;
			$session->closed_at = now();

			$this->buildSession($session);
		}
	}

	public function buildSession(Session $session)
	{
		$data = [];
		$keys = Redis::keys("$session->id.*");

		foreach ($keys as $key) {
			preg_match('/\.[A-Za-z0-9_-]+\.([A-Za-z0-9_\.]+)$/', $key, $matches);
			if (count($matches) !== 2)
				throw new Exception("Failed to extract stat name from `$key`");

			$name = $matches[1];
			$value = Redis::connection('input')->get($key);

			$data[ $name ] = $value;
		}

		$session->metrics = json_encode($data);
		$session->save();
	}

	public function getActiveSessions()
	{
		// TODO: meh
		return Session::where('active', true)->leftJoin('servers', 'sessions.server_id', '=', 'servers.id')->get([
			'sessions.id',
			'sessions.active',
			'sessions.server_id',
			'sessions.steamid',
			'servers.address',
			'sessions.created_at',
			'sessions.updated_at',
		]);
	}

	public function getActiveSessionManagersByServer()
	{
		return $this->getActiveSessions()->mapToGroups(function ($session) {
			return [$session['address'] => new SessionManager($session)];
		})->toArray();
	}

	public function create($steamid, $serverAddress)
	{
		/** @var ServerService $serverService */
		$serverService = app(ServerService::class);
		$server = $serverService->findServerByAddress($serverAddress);

		// This exception should never be thrown since EventDispatcher should filter servers that are not tracked
		if (!$server)
			throw new UntrackedServerException($serverAddress);

		// TODO: fillable
		$session = Session::make();

		$session->active = true;
		$session->server()->associate($server);
		$session->steamid = $steamid;

		$session->save();

		return $session;
	}
}