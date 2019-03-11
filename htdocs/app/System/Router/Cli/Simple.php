<?php

namespace App\System\Router\Cli;

use App\System\Router\Base;

class Simple extends Base
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
	 * @throws \ReflectionException
	 */
	public function run(&$controller, &$action, &$params)
	{
		$cliCommand = $this->getCliCommand();

		$match = $this->matchPathToRoute($cliCommand);

		if ($match !== false) {
			$this->assignByMatch($match, $controller, $action, $params);
		} else {
			$this->assignByPath($cliCommand, $controller, $action, $params);
		}

		return $this;
	}

	/**
	 * @param array $match
	 * @param string $controller
	 * @param string $action
	 * @param array $params
	 *
	 * @return $this
	 * @throws \ReflectionException
	 */
	protected function assignByMatch($match, &$controller, &$action, &$params)
	{
		$validIndexes = ['action', 'controller', 'params'];
		foreach ($match as $item => $value) {
			if (in_array($item, $validIndexes)) {
				if ($item === 'controller') {
					$shortName = (new \ReflectionClass($value))->getShortName();

					$start = strrpos($shortName, 'Controller');
					$value = substr_replace($shortName, '', $start);
				}

				$$item = $value;
			}
		}

		return $this;
	}

	/**
	 * @param string $path
	 * @param string $controller
	 * @param string $action
	 * @param array $params
	 *
	 * @return $this
	 */
	protected function assignByPath($path, &$controller, &$action, &$params)
	{
		if (is_string($path)) {
			$parts = explode('->', $path, 3);

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
