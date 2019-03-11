<?php

namespace App\System\Router;

abstract class Base
{
	abstract public function run(&$controller, &$action, &$params);
	abstract public function isCli();
}
