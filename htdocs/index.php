<?php

use App\System\Benchmark;
use App\System\Dispatcher;

define('BASEDIR', __DIR__);

require_once __DIR__ . '/../vendor/autoload.php';

$bench = (new Benchmark);

Dispatcher::instance()->run();

$timings = $bench->stopTimer('application_time')->getTimings();