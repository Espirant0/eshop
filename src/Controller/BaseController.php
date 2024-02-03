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
            echo 'page not found';
            return;
        }

        include_once $template;
    }
}