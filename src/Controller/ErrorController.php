<?php

namespace App\Controller;
use Core\Database\Repo\CategoryListRepo;
class ErrorController extends BaseController
{
	public function showErrorPage(): void
	{
		$this->render('ErrorPage/error.php',[]);
	}
}
