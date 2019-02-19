<?php

namespace App\System;

class Benchmark
{

	/**
	 * @var array
	 */
	private $timers = [];

	/**
	 * @var array
	 */
	private $timings = [];

	/**
	 * @var
	 */
	private $lastTimer;

	/**
	 * Benchmark constructor.
	 */
	public function __construct()
	{
		Registry::instance()->setReference(self::class, $this);

		$this->startTimer('application_time');
	}

	/**
	 * @param string $timerRef
	 *
	 * @return $this
	 */
	public function startTimer($timerRef)
	{
		$this->lastTimer = $timerRef;

		$this->timers[$timerRef] = microtime(true);

		return $this;
	}

	/**
	 * @param null|string $timerRef
	 *
	 * @return $this
	 */
	public function stopTimer($timerRef = null)
	{
		$end = microtime(true);
		if (!is_string($timerRef)) {
			$timerRef = $this->lastTimer;
		}

		if (isset($this->timers[$timerRef])) {
			$this->timings[$timerRef] = $end - $this->timers[$timerRef];

			unset($this->timers[$timerRef]);
		}

		return $this;
	}

	/**
	 * @param null|string $timing
	 *
	 * @return array|mixed
	 */
	public function getTimings($timing = null)
	{
		$timings = $this->timings;

		if (is_string($timing)) {
			$timings = $timings[$timing];
		}

		return $timings;
	}
}