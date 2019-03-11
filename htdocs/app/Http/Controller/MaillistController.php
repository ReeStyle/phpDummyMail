<?php

namespace App\Http\Controller;

use App\Http\Controller\Helper\MailUtilities;
use App\System\Http\Controller;
use App\System\Output\JsonModel;
use Exception;

class MaillistController extends Controller
{

	/**
	 * @return JsonModel
	 * @throws Exception
	 */
	public function grid()
	{
		$mails = (new MailUtilities())->getMailList();

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
