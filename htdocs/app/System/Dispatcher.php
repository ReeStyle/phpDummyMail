<?php

namespace App;

use App\System\Controller;
use App\System\Router\Simple;
use Exception;

require_once 'Controller.php';
require_once 'Router/Simple.php';

class Dispatcher
{

	/**
	 * @var string
	 */
	protected $controller;

	/**
	 * @var string
	 */
	protected $action;

	/**
	 * @var array
	 */
	protected $params = [];

	/**
	 * @return Dispatcher
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
	 * @return $this
	 * @throws Exception
	 */
	public function run()
	{
		if (php_sapi_name() !== 'cli') {

			$controllerFilename = $this
				->simpleRouteHandler()
				->getFullControllerName();

			require_once __DIR__ . '/../Http/Controller/' . $controllerFilename . '.php';

			$controllerClassName = 'App\Http\Controller\\' . $controllerFilename;

			/** @var Controller $controller */
			$controller = new $controllerClassName;

			if ($controller instanceof Controller) {
				$controller->__dispatch($this->action);
			} else {
				throw new Exception('Invalid controller, must implement ');
			}
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	public function simpleRouteHandler()
	{
		(new Simple())->run($this->controller, $this->action);

		return $this;
	}

	/**
	 * @return string
	 */
	public function getFullControllerName()
	{
		return ucfirst($this->controller) . 'Controller';
	}

	/**
	 * @return string
	 */
	public function getAction()
	{
		return $this->action;
	}

	/**
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * @param array $params
	 *
	 * @return $this
	 */
	public function setParams(array $params)
	{
		$this->params = $params;
		return $this;
	}
}