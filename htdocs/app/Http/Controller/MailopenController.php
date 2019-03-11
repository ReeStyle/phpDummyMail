<?php

namespace App\Http\Controller;

use App\Http\Controller\Helper\MailUtilities;
use App\System\Http\Controller;
use MS\Email\Parser\Parser;

class MailopenController extends Controller
{

	/**
	 * @return \TemplateerPHP\TemplateerPHP
	 * @throws \Exception
	 */
	public function content($mailId)
	{
		$content = (new MailUtilities())->getMail($mailId);

		$message = null;
		if ($content !== false) {
			$message = (new Parser())->parse($content);
		}

		return $this
			->getViewEngine()
			->setLayout('layout/mail')
			->assign([
				'message' => $message,
				'contentFound' => $content !== false,
 			]);
	}
}
