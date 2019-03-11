<?php

namespace App\System\Router;

use App\System\Registry;

abstract class Base
{

	/**
	 * @var array
	 */
	protected $routes = [];

	abstract public function run(&$controller, &$action, &$params);
	abstract public function isCli();

	/**
	 * Base constructor.
	 *
	 * @throws \Exception
	 */
	public function __construct()
	{
		Registry::set(self::class, $this);

		$this->loadRoutes();
	}

	/**
	 * @return Base
	 * @throws \Exception
	 */
	public function loadRoutes()
	{
		$type = $this->isCli() ? 'cli' : 'http';

		$routeFile = sprintf('%s/config/%s_routes.php', BASEDIR, $type);

		if (!file_exists($routeFile)) {
			throw new \Exception(sprintf('Could not find %s routes file at "%s"', strtoupper($type), $routeFile));
		}

		$routes = require $routeFile;

		if (!is_array($routes)) {
			throw new \Exception(sprintf('%s routes file "%s" did not return an array', strtoupper($type), $routeFile));
		}

		return $this->setRoutes($routes);
	}

	/**
	 * @param array $routes
	 *
	 * @return $this
	 */
	public function setRoutes(array $routes)
	{
		$this->routes = $routes;

		return $this;
	}

	/**
	 * @param $routeExpression
	 * @param array $routeDetails
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function setRoute($routeExpression, array $routeDetails)
	{
		if (is_string($routeExpression) && strlen($routeExpression) > 0) {
			$this->routes[$routeExpression] = $routeDetails;
		} else {
			throw new \Exception('Route was not a valid string');
		}

		return $this;
	}

	/**
	 * @param string $path
	 *
	 * @return mixed|null
	 */
	public function matchPathToRoute($path)
	{
		$result = null;

		$routes = &$this->routes;
		foreach ($routes as $routeExpression => $routeDetails) {
			$matchType = isset($routeDetails['match']) ? $routeDetails['match'] : 'literal';

			if ($matchType === 'literal') {
				if ($routeExpression === $path) {
					$result = $routeDetails;
					break;
				}
			}

			if ($matchType === 'regex') {
				$replace = [
					':any' => '(.+)',
					':num' => '([0-9]+)',
					':alpha' => '([A-z]+)',
					':alnum' => '([A-z0-9]+)',
				];
				$routeExpression = str_replace(array_keys($replace), array_values($replace), $routeExpression);
				$expression = sprintf('/%s/', $routeExpression);

				if (preg_match($expression, $path) > 0) {
					$result = $routeDetails;
					break;
				}
			}
		}

		return $result;
	}
}
