<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class UntrackedServerException extends Exception
{
	public function __construct($address)
	{
		parent::__construct("Server $address is not being tracked" .
			"");
	}
}
