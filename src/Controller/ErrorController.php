<?php

namespace App\Controller;
class ErrorController extends BaseController
{
	public function showErrorPage(): void
	{
		$this->render('ErrorPage/error.php',[]);
	}
}
