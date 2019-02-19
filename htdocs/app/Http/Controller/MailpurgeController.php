<?php

namespace App\Http\Controller;

use App\Http\Controller\Helper\MailExtractor;
use App\System\Config\Config;
use App\System\Controller;
use App\System\Output\JsonModel;
use App\System\Registry;

class MailpurgeController extends Controller
{

	/**
	 * @return JsonModel
	 * @throws \Exception
	 */
	public function index()
	{
		$success = false;
		if ($this->request()->isPost()) {
			/** @var Config $config */
			$config = Registry::instance()->getReference(Config::class);
			$mailFolder = $config->get('mails.folder');

			// Unlink "Remove" files
			$filePattern = $mailFolder . '/m_*.mail';
			$files = glob($filePattern, GLOB_MARK);
			foreach ($files as $file) {
				unlink($file);
			}

			// Create new, empty cache file
			$cacheFile = sprintf('%s/mail.cache', $mailFolder);
			file_put_contents($cacheFile, '');

			$success = true;
		}

		return (new JsonModel())->setData([
			'success' => $success,
		]);
	}
}
