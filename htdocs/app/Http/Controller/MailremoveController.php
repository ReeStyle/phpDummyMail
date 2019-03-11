<?php

namespace App\Http\Controller;

use App\Http\Controller\Helper\MailUtilities;
use App\System\Http\Controller;
use App\System\Output\JsonModel;

class MailremoveController extends Controller
{

	/**
	 * @return JsonModel
	 * @throws \Exception
	 */
	public function index()
	{
		$success = false;
		if ($this->request()->isPost()) {
			$mailIds = $this->request()->getFromPost('mailIds', []);

			(new MailUtilities())->removeMails($mailIds);

			$success = true;
		}

		return (new JsonModel())->setData([
			'success' => $success,
		]);
	}
}
