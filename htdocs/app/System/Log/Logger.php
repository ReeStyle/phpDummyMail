<?php

namespace App\System\Log;

use App\System\Registry;
use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger
{

	/**
	 * @var string
	 */
	private $logPath = '';

	/**
	 * Logger constructor.
	 *
	 * @param $logPath
	 *
	 * @throws \Exception
	 */
	public function __construct($logPath)
	{
		Registry::set(self::class, $this);

		$this->setLogPath($logPath);
	}

	/**
	 * @return string
	 */
	public function getLogPath()
	{
		return $this->logPath;
	}

	/**
	 * @param string $logPath
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function setLogPath($logPath)
	{
		if (is_dir($logPath)) {
			$this->logPath = $logPath;
		} else {
			throw new \Exception(sprintf('Path %s does not exist', $logPath));
		}
		return $this;
	}

	/**
	 * @param int $level
	 * @param string $message
	 * @param array $context
	 */
	public function log($level, $message, array $context = [])
	{
		$fileName = sprintf('%s/%s.log', $this->getLogPath(), date('Ymd'));

		$logString = sprintf('[%s] %s %s', date('c'), strtoupper($level), $message);

		file_put_contents($fileName, $logString . PHP_EOL	, FILE_APPEND);
	}
}
