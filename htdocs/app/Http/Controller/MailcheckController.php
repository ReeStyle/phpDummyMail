<?php

namespace App\Http\Controller;

use App\Http\Controller\Helper\MailExtractor;
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
		$mailCache = (new MailExtractor())->rebuildCache();

		return (new JsonModel())->setData([
			'success' => true,
			'filesProcessed' => count($mailCache),
			'mailCache' => $mailCache,
		]);
	}
}
