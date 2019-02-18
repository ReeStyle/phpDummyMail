<?php

$sendMailPath = ini_get('sendmail_path');

printf('php.ini file: %s' . PHP_EOL, php_ini_loaded_file ());
printf('Sendmail path: %s' . PHP_EOL, $sendMailPath);

if (basename($sendMailPath) !== 'receiver.php') {
	printf('Cannot send test mail. Alter your php.ini file at %s to sendmail_path = %s/receiver.php' . PHP_EOL, dirname(php_ini_loaded_file ()), __DIR__);
} else {

	mail('test@null.com', 'test', 'test', 'From: info@nowhere.com');

	print 'Test message sent' . PHP_EOL;
}

