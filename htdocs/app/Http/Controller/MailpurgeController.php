<?php

namespace App\Http\Controller;

use App\Http\Controller\Helper\MailUtilities;
use App\System\Http\Controller;
use App\System\Output\JsonModel;

class MailpurgeController extends Controller
{

	/**
	 * @return JsonModel
	 * @throws \Exception
	 */
	public function index()
	{
		$success = false;
		if ($this->request()->isPost()) {
			(new MailUtilities())->purgeMails();

			$success = true;
		}

		return (new JsonModel())->setData([
			'success' => $success,
		]);
	}
}
