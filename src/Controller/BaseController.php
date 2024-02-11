<?php

namespace App\Controller;
abstract class BaseController
{
    public function render(string $templateName, array $params): void
    {
        $template = __DIR__ . '/../View/' . $templateName;

        if (!file_exists($template))
        {
            http_response_code(404);
            #echo 'page not found';
			include_once __DIR__ . '/../View/NotFoundPage/404.php';
            return;
        }

		extract($params);
		include_once $template;
    }
	public function strRender(string $templateName, array $params): ?string
	{
		$template = __DIR__ . '/../View/' . $templateName;

		if (!file_exists($template))
		{
			http_response_code(404);
			#echo 'page not found';

			ob_start();
			include_once __DIR__ . '/../View/NotFoundPage/404.php';
			return ob_get_clean();
		}

		extract($params);

		ob_start();
		include_once $template;
		return ob_get_clean();
	}
<<<<<<< 206341a4fb6ab5cbdcd0026864b54ef6eea92fba
=======

	public function checkAuth(): bool
	{
		session_start();

		if (!isset($_SESSION['USER']))
		{
			return false;
		}

		return true;
	}
>>>>>>> 39d6355c989bdad7fdd1d587c43dd5be04643151
}