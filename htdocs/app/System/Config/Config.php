<?php

namespace App\System\Config;

use App\System\Registry;

class Config
{

	/**
	 * @var array
	 */
	private $settings = [];

	/**
	 * Config constructor
	 */
	public function __construct()
	{
		Registry::instance()->setReference(self::class, $this);
	}

	/**
	 * @param string $ref
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function get($ref, $default = null)
	{
		$result = $default;

		$roadSections = explode('.', $ref);
		if (array_key_exists($roadSections[0], $this->settings)) {
			$tmpResult = $this->settings;

			$found = true;
			while ($section = array_shift($roadSections)) {
				if (!array_key_exists($section, $tmpResult)) {
					$found = false;
					break;
				}
				$tmpResult = $tmpResult[$section];
			}

			if ($found) {
				$result = $tmpResult;
			}
		}

		return $result;
	}

	/**
	 * @return $this
	 */
	public function run()
	{
		$configData = require BASEDIR . '/config/config.php';

		$configs = ['ini_set', 'php_fault_control', 'application', 'mails'];

		function camelize($input, $separator = '_')
		{
			return lcfirst(str_replace($separator, '', ucwords($input, $separator)));
		}

		foreach ($configs as $config) {
			if (isset($configData[$config]) && is_array($configData[$config])) {
				call_user_func([$this, camelize($config)], $configData[$config]);
			}
		}


		return $this;
	}

	/**
	 * @param array $settings
	 *
	 * @return $this
	 */
	public function iniSet(array $settings)
	{
		foreach ($settings as $setting => $value) {
			ini_set($setting, $value);
		}

		return $this;
	}

	/**
	 * @param array $settings
	 *
	 * @return $this
	 */
	public function phpFunc(array $settings)
	{
		foreach ($settings as $function => $value) {
			call_user_func($function, $value);
		}

		return $this;
	}

	/**
	 * @param array $settings
	 */
	public function application(array $settings)
	{
		$this->settings['application'] = $settings;
	}

	/**
	 * @param array $settings
	 */
	public function mails(array $settings)
	{
		$this->settings['mails'] = $settings;
	}
}