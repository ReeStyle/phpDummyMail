<?php

namespace App\Http\Controller\Helper;

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
}
