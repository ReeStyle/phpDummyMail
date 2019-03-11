<?php

namespace App\Http\Controller;

use App\System\Http\Controller;

class IndexController extends Controller
{

	/**
	 * @return \TemplateerPHP\TemplateerPHP
	 * @throws \Exception
	 */
	public function index()
	{
		return $this
			->getViewEngine()
			->setData([
				'pageTitle' => 'phpDummyMail',
			]);
	}
}
