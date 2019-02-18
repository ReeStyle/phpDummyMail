<?php

namespace App\System\Interfaces;

use Exception;

interface Controller
{
	/**
	 * @param string $action
	 *
	 * @throws Exception
	 */
	public function __dispatch($action);
}