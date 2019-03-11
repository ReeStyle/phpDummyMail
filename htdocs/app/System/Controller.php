<?php

namespace App\System;

use App\System\Http\Request;
use App\System\Interfaces\Output;
use App\System\Config\Config as SystemConfig;
use App\System\View\Helper\Config;
use TemplateerPHP\TemplateerPHP;
use Exception;
use App\System\Interfaces\Controller as ControllerInterface;

abstract class Controller
implements ControllerInterface
{

	const CURRENT_CONTROLLER = 'current_controller';
	const REQUEST = 'request';

	/**
	 * @param string $action
	 *
	 * @throws Exception
	 */
	public final function __dispatch($action)
	{
		Registry::instance()->setReference(self::CURRENT_CONTROLLER, $this);

		if ($action === '__dispatch') {
			print 'Loop hole!';
			exit();
		}

		$controllerName = get_class($this);
		if (!method_exists($this, $action)) {
			throw new Exception(sprintf('%s does not contain action %s', $controllerName, $action));
		}

		$return = call_user_func_array([$this, $action], Dispatcher::instance()->getParams());

		$this->__stream($return, $action);
	}

	/**
	 * @return Request
	 */
	protected function request()
	{
		$request = Registry::instance()->getReference(self::REQUEST, false);

		if ($request === false) {
			$request = new Request();
		}

		return $request;
	}

	/**
	 * @param TemplateerPHP $return
	 * @param string $action
	 *
	 * @throws Exception
	 */
	private function __stream($return, $action)
	{
		$actualControllerName = (new \ReflectionClass($this))->getShortName();

		if ($return instanceof TemplateerPHP) {
			$endActualControllerName = strrpos($actualControllerName, 'Controller');
			$viewBase = strtolower(substr($actualControllerName, 0, $endActualControllerName));

			if ($return->getResource() === null) {
				$resource = sprintf('%s/%s', $viewBase, $action);
				$return->setResource($resource);
			}

			/** @var SystemConfig $config */
			$config = Registry::instance()->getReference(SystemConfig::class);

			$useMinified = $config->get('templateer.use_minified_js');
			$return->assign('use_minified_js', $useMinified);

			$return->render();
		}

		if ($return instanceof Output) {
			$return->stream();
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

			$resourceBaseDir = __DIR__ . '/../Http/views';

			$viewEngine
				->setBaseDir($resourceBaseDir)
				->addHelper(Config::class);
		}

		return $viewEngine;
	}
}
