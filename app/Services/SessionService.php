<?php

namespace App\Services;

use App\Server;
use App\Session;

class SessionService
{
	public function create($steamid, Server $server)
	{
		// TODO: fillable
		$session = Session::make();

		$session->active = true;
		$session->server()->associate($server);
		$session->steamid = $steamid;

		$session->save();

		return $session;
	}
}