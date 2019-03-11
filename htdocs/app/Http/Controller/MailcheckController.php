<?php

namespace App\Http\Controller;

use App\Http\Controller\Helper\MailUtilities;
use App\System\Controller;
use App\System\Output\JsonModel;

class MailcheckController extends Controller
{

	/**
	 * @return JsonModel
	 * @throws \Exception
	 */
	public function index()
	{
		$mailCache = (new MailUtilities())->rebuildCache();

		return (new JsonModel())->setData([
			'success' => true,
			'filesProcessed' => count($mailCache),
			'mailCache' => $mailCache,
		]);
	}
}
