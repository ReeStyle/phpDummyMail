<?php

ini_set('display_errors', 'on');

require_once __DIR__ . '/app/System/Dispatcher.php';

\App\Dispatcher::instance()->run();