<?php

namespace App\Classes;

use App\Exceptions\MissingStatNameException;

abstract class Stat
{
	protected $builder;

	protected $data;
	protected $cache;
	protected $inited = false;

	protected $dependencies = [];
	protected $name;

	public function __construct(SessionBuilder $builder)
	{
		$this->builder = $builder;
		if (!$this->name)
			throw new MissingStatNameException(static::class);
	}

	public function getStat(string $class)
	{
		return $this->builder->getStat($class);
	}

	public function getDependencies()
	{
		return $this->dependencies;
	}

	public function getName()
	{
		return $this->name;
	}

	public function init(array $data, $force = false)
	{
		if (!$this->inited() || $force) {
			$this->data = $data;
			$this->cache = $this->compute();
			$this->inited = true;
		} else {
			$name = static::class;
			info("Skipped initialization of $name (already loaded)");
		}
	}

	public function inited(): bool
	{
		return $this->inited;
	}

	public function getValue()
	{
		return $this->cache;
	}

	abstract protected function compute();
}