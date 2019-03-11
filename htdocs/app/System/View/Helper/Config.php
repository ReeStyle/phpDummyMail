<?php

namespace App\System\View\Helper;

use App\System\Registry;
use TemplateerPHP\Helper\Base;
use App\System\Config\Config as AppConfig;

class Config extends Base
{

	public function __invoke($configItem)
	{
		/** @var AppConfig $config */
		$config = Registry::get(AppConfig::class);

		return $config->get($configItem);
	}

}
