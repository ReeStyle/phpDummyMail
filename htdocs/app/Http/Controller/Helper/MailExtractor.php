<?php

namespace App\Http\Controller\Helper;

use App\System\Config\Config;
use App\System\Registry;

class MailExtractor
{

	/**
	 * @param string $filePath
	 *
	 * @return array
	 */
	public function getBaseInfoFromFile($filePath)
	{
		$baseName = basename($filePath);
		$mailId = str_replace('.mail', '', $baseName);

		$content = file_get_contents($filePath);

		$parts = [];
		preg_match('/^To: (.+)$/m', $content, $parts);
		$to = $parts[1];

		$parts = [];
		preg_match('/^From: (.+)$/m', $content, $parts);
		$from = count($parts) > 0 ? $parts[1] : null;

		$parts = [];
		preg_match('/^Subject: (.+)$/m', $content, $parts);
		$subject = count($parts) > 0 ? $parts[1] : null;

		return sprintf('%s|%s|%s|%s', $mailId, $to, $from, $subject);
	}

	/**
	 * @param string $filePath
	 *
	 * @return array
	 */
	public function pipeSeparatedFileToArray($filePath)
	{
		$contents = file($filePath);

		$output = [];
		foreach ($contents as $row) {
			$row = explode('|', $row, 4);

			$mailId = $row[0];
			$to = $row[1];
			$from = $row[2];
			$subject = $row[3];

			$output[$mailId] = [
				'to' => $to,
				'from' => $from,
				'subject' => $subject,
			];
		}

		return $output;
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	public function rebuildCache()
	{
		/** @var Config $config */
		$config = Registry::instance()->getReference(Config::class);
		$mailFolder = $config->get('mails.folder');

		if (!$mailFolder) {
			throw new \Exception('Mail folder not set');
		}

		$cacheFile = sprintf('%s/mail.cache', $mailFolder);
		$filePattern = $mailFolder . '/m_*.mail';
		$files = glob($filePattern, GLOB_MARK);

		$mailCache = [];
		foreach ($files as $file) {
			$mailId = basename($file);

			$parts = $this->getBaseInfoFromFile($file);

			$mailCache[$mailId] = $parts;
		}

		file_put_contents($cacheFile, implode(PHP_EOL, $mailCache));

		return $mailCache;
	}
}
