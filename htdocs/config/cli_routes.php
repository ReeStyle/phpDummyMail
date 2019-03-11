<?php

return [
	'receiver.run' => [
		'match' => 'literal',
		'controller' => \App\Cli\Controller\ReceiverController::class,
		'action' => 'run',
	],
];