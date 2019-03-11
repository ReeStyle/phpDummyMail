<?php

namespace App\System\Cli;

use App\System\Dispatcher;
use App\System\Registry;
use League\CLImate\CLImate;

abstract class Controller
{
	const CURRENT_CONTROLLER = 'current_controller';

	/**
	 * @param string $action
	 *
	 * @throws \Exception
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
			throw new \Exception(sprintf('%s does not contain action %s', $controllerName, $action));
		}

		call_user_func_array([$this, $action], Registry::get(Dispatcher::class)->getParams());
	}

	/**
	 * @return CLImate
	 */
	protected function climate()
	{
		if (!Registry::get(CLImate::class)) {
			Registry::set(CLImate::class, new CLImate());
		}

		return Registry::get(CLImate::class);
	}
}
