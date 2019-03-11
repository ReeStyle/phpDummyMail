<?php

namespace App\Cli\Controller;

use App\System\Cli\Controller;
use App\System\Config\Config;
use App\System\Log\Logger;
use App\System\Registry;

class ReceiverController extends Controller
{
	public function run()
	{
		$buffer = '';
		while (!feof(STDIN)) {
			$buffer .= fgets(STDIN);
		}

		$this->storeMail($buffer);
	}

	private function storeMail($buffer)
	{
		$logDir = Registry::get(Config::class)->get('application.logdir');
		$mailDir = Registry::get(Config::class)->get('mails.folder');
		if (!is_dir($mailDir)) {
			mkdir($mailDir, 0775);
		}

		$microseconds = explode(' ', microtime(), 2)[1];
		$dateThinger = date('YmdHis') . substr($microseconds, 0, 5);

		$fileName = $mailDir . '/m_' .  $dateThinger . '.mail';

		(new Logger($logDir))->info($fileName);

		file_put_contents($fileName, $buffer);
	}
}
