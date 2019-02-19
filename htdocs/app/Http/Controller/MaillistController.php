<?php

namespace App\Http\Controller;

use App\Http\Controller\Helper\MailExtractor;
use App\System\Config\Config;
use App\System\Controller;
use App\System\Output\JsonModel;
use App\System\Registry;
use TemplateerPHP\TemplateerPHP;
use Exception;

class MaillistController extends Controller
{

	/**
	 * @return JsonModel
	 * @throws Exception
	 */
	public function grid()
	{
		/** @var Config $config */
		$config = Registry::instance()->getReference(Config::class);
		$mailFolder = $config->get('mails.folder');

		$cacheFile = sprintf('%s/mail.cache', $mailFolder);

		$mails = (new MailExtractor())->pipeSeparatedFileToArray($cacheFile);

		$html = $this
			->getViewEngine()
			->setImplicitLayout(false)
			->assign([
				'mails' => $mails,
				'test' => 'Hello world',
			])->render('maillist/grid', false);

		return (new JsonModel())->setData([
			'html' => $html,
			'mailCount' => count($mails),
		]);
	}
}
