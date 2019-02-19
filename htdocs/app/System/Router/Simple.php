<?php

namespace App\System\Router;

use App\System\Registry;

class Simple
{
	public function __construct()
	{
		Registry::instance()->setReference(self::class, $this);
	}

	/**
	 * @param string $controller
	 * @param string $action
	 *
	 * @return $this
	 */
	public function run(&$controller, &$action, &$params)
	{
		$controller = 'index';
		$action = 'index';

		if (array_key_exists('PATH_INFO', $_SERVER)) {
			$pathInfo = trim($_SERVER['PATH_INFO'], '/');
			if (!is_null($pathInfo) && strlen($pathInfo) > 0) {
				$parts = explode('/', $pathInfo);

				foreach ([0 => 'controller', 1 => 'action'] as $index => $part) {
					if (isset($parts[$index])) {
						${$part} = $parts[$index];

						unset($parts[$index]);
					}
				}

				/// whatever is left
				$params = $parts;
			}
		}

		return $this;
	}
}
