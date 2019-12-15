<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class MultipleSessionsException extends Exception
{
	public function __construct($steamid)
	{
		parent::__construct("SteamID $steamid has multiple active sessions");
	}
}
