<?php

namespace App\System\Router;

class CliSimple extends Base
{

	/**
	 * @var null|string
	 */
	private $cliCommand;

	/**
	 * @return bool
	 */
	public function isCli()
	{
		return true;
	}

	/**
	 * @return null|string
	 */
	public function getCliCommand()
	{
		return $this->cliCommand;
	}

	/**
	 * @param null|string $cliCommand
	 *
	 * @return $this
	 */
	public function setCliCommand($cliCommand)
	{
		$this->cliCommand = $cliCommand;

		return $this;
	}

	/**
	 * @param string $controller
	 * @param string $action
	 * @param string $params
	 *
	 * @return $this
	 */
	public function run(&$controller, &$action, &$params)
	{
		$cliCommand = $this->getCliCommand();
		if (is_string($cliCommand)) {
			$parts = explode('->', $cliCommand, 3);

			$partCount = count($parts);
			if ($partCount > 0) {
				$controller = $parts[0];
			}

			if ($partCount > 1) {
				$action = $parts[1];
			}

			if ($parts > 2) {
				$params = $parts;
			}
		}

		return $this;
	}

}