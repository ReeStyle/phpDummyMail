<?php

$autoloader = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoloader)) {
	print 'You may need to run "composer install" or "composer dumpautoload"';
	exit(1);
}

use App\System\Benchmark;
use App\System\Dispatcher;
use App\System\Config\Config;

define('BASEDIR', __DIR__);

require_once $autoloader;

$bench = (new Benchmark);

(new Config())->run();

(new Dispatcher())->run();

$timings = $bench->stopTimer('application_time')->getTimings();