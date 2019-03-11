<?php

namespace App\Http\Controller;

use App\System\Config\Config;
use App\System\Controller;
use App\System\Registry;
use MS\Email\Parser\Parser;

class MailopenController extends Controller
{

	/**
	 * @return \TemplateerPHP\TemplateerPHP
	 * @throws \Exception
	 */
	public function content($mailId)
	{
		/** @var Config $config */
		$config = Registry::instance()->getReference(Config::class);
		$mailFolder = $config->get('mails.folder');

		$content = '';
		$contentFound = false;

		$mailFilePath = sprintf('%s/%s.mail', $mailFolder, $mailId);
		if (file_exists($mailFilePath)) {
			$content = file_get_contents($mailFilePath);
			$contentFound = true;
		}

		$message = (new Parser())->parse($content);

		return $this
			->getViewEngine()
			->setLayout('layout/mail')
			->assign([
				'message' => $message,
				'contentFound' => $contentFound,
 			]);
	}
}
