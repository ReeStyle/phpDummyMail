<?php

namespace App\System\Http;

use App\System\Controller;
use App\System\Registry;

class Request
{

	/**
	 * Request constructor.
	 */
	public function __construct()
	{
		Registry::instance()->setReference(Controller::REQUEST, $this);
	}

	/**
	 * @return bool
	 */
	public function isPost()
	{
		return $_SERVER['REQUEST_METHOD'] === 'POST';
	}

}