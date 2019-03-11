#!/usr/bin/env php
<?php

use App\System\Benchmark;
use App\System\Dispatcher;
use App\System\Config\Config;

require __DIR__ . '/vendor/autoload.php';

define('BASEDIR', __DIR__ . '/htdocs');

(new Benchmark());

(new Config())->run();

(new Dispatcher())->run('receiver.run');

(new Benchmark())->stopTimer('application_timer');

exit(0);
