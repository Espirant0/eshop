<?php

namespace App\Controller;
class ErrorController extends BaseController
{
	public function showErrorPage(): void
	{
		ob_end_clean();
		echo $this->render('ErrorPage/error.php', ['title' => 'Ошибка!']);
		die();
	}
}