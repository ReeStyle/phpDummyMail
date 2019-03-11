<?php

namespace App\System\Http;

use App\System\Dispatcher;
use App\System\Interfaces\Output;
use App\System\Config\Config as SystemConfig;
use App\System\Registry;
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
		Registry::set(self::CURRENT_CONTROLLER, $this);

		if ($action === '__dispatch') {
			print 'Loop hole!';
			exit();
		}

		$controllerName = get_class($this);
		if (!method_exists($this, $action)) {
			throw new Exception(sprintf('%s does not contain action %s', $controllerName, $action));
		}

		$return = call_user_func_array([$this, $action], Registry::get(Dispatcher::class)->getParams());

		$this->__stream($return, $action);
	}

	/**
	 * @return Request
	 */
	protected function request()
	{
		$request = Registry::get(self::REQUEST, false);

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
			$config = Registry::get(SystemConfig::class);

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

			$resourceBaseDir = Registry::get(SystemConfig::class)->get('application.templates');

			$viewEngine
				->setBaseDir($resourceBaseDir)
				->addHelper(Config::class);
		}

		return $viewEngine;
	}
}
