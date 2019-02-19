<?php

namespace App\Http\Controller;

use App\Http\Controller\Helper\MailExtractor;
use App\System\Config\Config;
use App\System\Controller;
use App\System\Output\JsonModel;
use App\System\Registry;

class MailcheckController extends Controller
{

	/**
	 * @return JsonModel
	 * @throws \Exception
	 */
	public function index()
	{
		/** @var Config $config */
		$config = Registry::instance()->getReference(Config::class);
		$mailFolder = $config->get('mails.folder');

		$cacheFile = sprintf('%s/mail.cache', $mailFolder);
		$filePattern = $mailFolder . '/m_*.mail';
		$files = glob($filePattern, GLOB_MARK);

		$extractor = new MailExtractor();

		$mailCache = [];
		foreach ($files as $file) {
			$mailId = basename($file);

			$parts = $extractor->getBaseInfoFromFile($file);

			$mailCache[$mailId] = $parts;
		}

		file_put_contents($cacheFile, implode(PHP_EOL, $mailCache));

		return (new JsonModel())->setData([
			'success' => true,
			'filesProcessed' => count($files),
			'mailCache' => $mailCache,
		]);
	}
}
