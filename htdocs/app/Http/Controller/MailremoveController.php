<?php

namespace App\Http\Controller;

use App\Http\Controller\Helper\MailExtractor;
use App\System\Config\Config;
use App\System\Controller;
use App\System\Output\JsonModel;
use App\System\Registry;

class MailremoveController extends Controller
{

	/**
	 * @return JsonModel
	 * @throws \Exception
	 */
	public function index()
	{
		$success = false;
		if ($this->request()->isPost()) {
			$mailIds = $this->request()->getFromPost('mailIds');

			/** @var Config $config */
			$config = Registry::instance()->getReference(Config::class);
			$mailFolder = $config->get('mails.folder');

			// Unlink "Remove" files
			$filePattern = '%s/%s.mail';
			foreach ($mailIds as $mailId) {
				print $file = sprintf($filePattern, $mailFolder, $mailId);

				if (file_exists($file)) {
					unlink($file);
				}
			}

			(new MailExtractor())->rebuildCache();

			$success = true;
		}

		return (new JsonModel())->setData([
			'success' => $success,
		]);
	}
}
