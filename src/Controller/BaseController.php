<?php

namespace App\Controller;
abstract class BaseController
{
	public function render(string $templateName, array $params): string
	{
		$template = __DIR__ . '/../View/' . $templateName;

		if (!file_exists($template))
		{
			http_response_code(404);

            ob_start();
			include_once __DIR__ . '/../View/NotFoundPage/404.php';
			return ob_get_clean();
		}

        extract($params);

        ob_start();
        require $template;
        return ob_get_clean();
	}
}