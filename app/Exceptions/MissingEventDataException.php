<?php

namespace App\Exceptions;

use Exception;

class MissingEventDataException extends Exception
{
	public function __construct($expected)
	{
		parent::__construct("Expected key `$expected` does not exist in event.");
	}
}
