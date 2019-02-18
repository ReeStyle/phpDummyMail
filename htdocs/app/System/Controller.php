<?php

namespace App\System;

use TemplateerPHP\TemplateerPHP;
use Exception;
use App\System\Interfaces\Controller as ControllerInterface;

abstract class Controller
implements ControllerInterface
{

	/**
	 * @param string $action
	 *
	 * @throws Exception
	 */
	public final function __dispatch($action)
	{
		if ($action === '__dispatch') {
			print 'Loop hole!';
			exit();
		}

		$controllerName = get_class($this);
		if (!method_exists($this, $action)) {
			throw new Exception(sprintf('%s does not contain action %s', $controllerName, $action));
		}

		$return = call_user_func_array([$this, $action], Dispatcher::instance()->getParams());

		$this->__render($return, $action);
	}

	/**
	 * @param $return
	 *
	 * @throws Exception
	 */
	private function __render($return, $action)
	{
		$controllerName = get_class($this);

		if ($return instanceof TemplateerPHP) {


			$startActualName = strrpos($controllerName, '\\') + 1;
			$actualName = substr($controllerName, $startActualName);

			$endActualName = strrpos($actualName, 'Controller');
			$viewName = strtolower(substr($actualName, 0, $endActualName));
			$resource = sprintf('%s/%s', $viewName, $action);

			if ($return->getResource() === null) {
				$return->setResource($resource);
			}

			$return->render();
		}
	}

	/**
	 * @return TemplateerPHP
	 * @throws Exception
	 */
	public function getViewEngine()
	{
		static $viewEngine;

		if ($viewEngine === null) {
			$viewEngine = new TemplateerPHP();

			$resourceBaseDir = __DIR__ . '/../views';

			$viewEngine->setBaseDir($resourceBaseDir);
		}

		return $viewEngine;
	}
}
