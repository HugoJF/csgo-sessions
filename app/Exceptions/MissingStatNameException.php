<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class MissingStatNameException extends Exception
{
	public function __construct($class)
	{
		parent::__construct("Missing name for stat $class");
	}
}
