<?php

namespace App\System;

use App\System\Router\CliSimple;
use App\System\Router\Simple;
use Exception;
use App\System\Http\Controller as HttpContoller;
use App\System\Cli\Controller as CliContoller;


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
	 * Dispatcher constructor.
	 */
	public function __construct()
	{
		Registry::set(self::class, $this);
	}

	/**
	 * @return $this
	 * @throws Exception
	 */
	public function run($cliCommand = null)
	{
		$controllerBase = php_sapi_name() === 'cli' ? 'Cli' : 'Http';

		$controllerInstanceCheck = php_sapi_name() === 'cli' ? CliContoller::class : HttpContoller::class;

		$controllerFilename = $this
			->simpleRouteHandler($cliCommand)
			->getFullControllerName();

		$controllerClassName = sprintf('App\%s\Controller\%s', $controllerBase, $controllerFilename);

		/** @var Controller $controller */
		$controller = new $controllerClassName;

		if ($controller instanceof $controllerInstanceCheck) {
			$controller->__dispatch($this->action);
		} else {
			throw new Exception('Invalid controller, must implement ');
		}

		return $this;
	}

	/**
	 * @param string|null $cliCommand
	 *
	 * @return $this
	 */
	public function simpleRouteHandler($cliCommand = null)
	{
		$routerClass = php_sapi_name() !== 'cli' ? Simple::class : CliSimple::class;

		/** @var Simple|CliSimple $router */
		$router = new $routerClass();

		if ($router->isCli() && is_string($cliCommand)) {
			$router->setCliCommand($cliCommand);
		}

		$router->run($this->controller, $this->action, $this->params);

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