<?php

namespace App\Http\Controller;

use App\System\Http\Controller;
use League\CommonMark\CommonMarkConverter;
use TemplateerPHP\TemplateerPHP;

class InfoController extends Controller
{

	/**
	 * @return TemplateerPHP
	 * @throws \Exception
	 */
	public function licenses()
	{
		$license = file_get_contents(BASEDIR . '/../LICENSE');

		$additionalLicenses = file_get_contents(BASEDIR . '/../licenses.md');

		$converter = new CommonMarkConverter();

		return $this->getViewEngine()
			->assign([
				'license' => $converter->convertToHtml($license),
				'additionalLicenses' => $converter->convertToHtml($additionalLicenses),
			])->setImplicitLayout(false);
	}
}
