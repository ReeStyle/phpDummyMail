<?php

namespace App\System;

class Registry
{

	/**
	 * @var array
	 */
	private $registry = [];

	/**
	 * @return Registry
	 */
	public static function instance()
	{
		static $instance;

		if ($instance === null) {
			$instance = new self;
		}

		return $instance;
	}

	/**
	 * @param string $ref
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function setReference($ref, $value)
	{
		$this->registry[$ref] = $value;

		return $this;
	}

	/**
	 * @param string $ref
	 * @param mixed $default
	 *
	 * @return mixed|null
	 */
	public function getReference($ref, $default = null)
	{
		$result = $default;
		if (array_key_exists($ref, $this->registry)) {
			$result = $this->registry[$ref];
		}

		return $result;
	}
}
