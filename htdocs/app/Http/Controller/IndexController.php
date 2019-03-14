<?php

namespace App\Http\Controller;

use App\Http\Controller\Helper\MailUtilities;
use App\System\Http\Controller;

class IndexController extends Controller
{

	/**
	 * @return \TemplateerPHP\TemplateerPHP
	 * @throws \Exception
	 */
	public function index()
	{
		(new MailUtilities())->rebuildCache();

		return $this
			->getViewEngine()
			->setData([
				'pageTitle' => 'phpDummyMail',
			]);
	}
}
