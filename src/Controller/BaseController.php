<?php

namespace App\Controller;
abstract class BaseController
{
    public function render(string $templateName, array $params)
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

        if ($templateName !== 'layout.php')
        {
            ob_start();
            include_once $template;
            return ob_get_clean();
        }

        include_once $template;
    }
}