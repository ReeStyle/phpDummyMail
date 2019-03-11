<?php

namespace App\Http\Controller\Helper;

use App\System\Config\Config;
use App\System\Registry;

class MailUtilities
{

	/**
	 * @return string
	 */
	private function getMailFolder()
	{
		/** @var Config $config */
		$config = Registry::instance()->getReference(Config::class);
		$mailFolder = $config->get('mails.folder');

		return $mailFolder;
	}

	/**
	 * @param string $mailId
	 *
	 * @return bool|string
	 */
	public function getMail($mailId)
	{
		$mailFolder = $this->getMailFolder();

		$content = '';
		$contentFound = false;

		$mailFilePath = sprintf('%s/%s.mail', $mailFolder, $mailId);
		if (file_exists($mailFilePath)) {
			$content = file_get_contents($mailFilePath);
			$contentFound = true;
		}

		return $contentFound ? $content : false;
	}

	/**
	 * @return array
	 */
	public function getMailList()
	{
		$mailFolder = $this->getMailFolder();

		$cacheFile = sprintf('%s/mail.cache', $mailFolder);

		return $this->pipeSeparatedFileToArray($cacheFile);
	}

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
		$mailFolder = $this->getMailFolder();

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

	/**
	 * @param array $mailIds
	 *
	 * @return int
	 * @throws \Exception
	 */
	public function removeMails(array $mailIds)
	{
		$mailFolder = $this->getMailFolder();

		// Unlink "Remove" files
		$filePattern = '%s/%s.mail';
		$returnCount = 0;
		foreach ($mailIds as $mailId) {
			$file = sprintf($filePattern, $mailFolder, $mailId);

			if (file_exists($file)) {
				unlink($file);
				$returnCount++;
			}
		}

		$this->rebuildCache();

		return $returnCount;
	}

	/**
	 * @return int
	 */
	public function purgeMails()
	{
		$mailFolder = $this->getMailFolder();

		// Unlink "Remove" files
		$filePattern = $mailFolder . '/m_*.mail';
		$files = glob($filePattern, GLOB_MARK);
		foreach ($files as $file) {
			unlink($file);
		}

		// Create new, empty cache file
		$cacheFile = sprintf('%s/mail.cache', $mailFolder);
		file_put_contents($cacheFile, '');

		return count($files);
	}
}
