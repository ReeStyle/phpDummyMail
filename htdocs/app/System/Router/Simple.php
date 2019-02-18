<?php

namespace App\System\Router;

class Simple
{

	/**
	 * @param string $controller
	 * @param string $action
	 *
	 * @return $this
	 */
	public function run(&$controller, &$action)
	{
		$controller = 'index';
		$action = 'index';

		if (array_key_exists('PATH_INFO', $_SERVER)) {
			$pathInfo = $_SERVER['PATH_INFO'];
			if (!is_null($pathInfo)) {
				$parts = explode('/', trim($pathInfo, '/'));

				foreach ([0 => 'controller', 1 => 'action'] as $index => $part) {
					if (isset($parts[$index])) {
						${$part} = $parts[$index];

						unset($parts[$index]);
					}
				}

				/// whatever is left
				$this->params = $parts;
			}
		}

		return $this;
	}

}