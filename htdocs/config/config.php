<?php

return [
	'ini_set' => [
		'display_errors' => 'on',
	],
	'php_func' => [
		'error_reporting' => E_ALL,
	],
	'application' => [
		'action_method' => 'pathinfo',
		'webroot' => '/phpdummymail',
		'approot' => '/phpdummymail/index.php',
	],
	'mails' => [
		'folder' => __DIR__ . '/../../mails',
	],
];