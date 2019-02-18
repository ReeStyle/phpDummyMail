#!/usr/bin/env php
<?php

$crlf = "\r\n";
logThis(str_repeat('-', 100));
logThis(date('Y-m-d H:i:s'));
logThis('ARGV: ' . implode(PHP_EOL, $argv));
logThis('ARGC: ' . $argc);

//Sendmail path: /var/www/domains/phpdummymail/receiver.php
//To: test@null.com
//Subject: test
//X-PHP-Originating-Script: 1000:tester.php
//
//test

//$fh = fopen('php://input', 'r');

$buffer = '';
while (!feof(STDIN)) {
	$buffer .= fgets(STDIN);
}

storeMail($buffer);

exit(0);

function storeMail($buffer)
{
	$mailDir = __DIR__ . '/mails';
	if (!is_dir($mailDir)) {
		mkdir($mailDir, 0775);
	}

	$microseconds = explode(' ', microtime(), 2)[1];
	$dateThinger = date('YmdHis') . substr($microseconds, 0, 5);

	$fileName = $mailDir . '/m_' .  $dateThinger . '.mail';

	file_put_contents($fileName, $buffer);
}

function logThis($what)
{
	$logDir = __DIR__ . '/logs';
	if (!is_dir($logDir)) {
		mkdir($logDir, 0775);
	}

	$fileName = sprintf('%s/%s.log', $logDir, date('Ymd'));
	file_put_contents($fileName, $what . PHP_EOL	, FILE_APPEND);
}