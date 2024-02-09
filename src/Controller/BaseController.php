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

	public function checkAuth():bool
	{
		session_start();
		if(!isset($_SESSION['USER']))
		{
			return false;
		}
		return true;
	}
}