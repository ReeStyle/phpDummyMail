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

	/**
	 * @param string|array $filter
	 * @param mixed $default
	 *
	 * @return array|null
	 */
	public function getFromPost($filter, $default = null)
	{
		if (is_array($filter)) {
			$output = [];

			foreach ($filter as $index) {
				if (array_key_exists($index, $_POST)) {
					$output[$index] = $_POST[$index];
				}
			}
		} else {
			$output = array_key_exists($filter, $_POST) ? $_POST[$filter] : $default;
		}

		return $output;
	}
}
