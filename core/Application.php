<?php

namespace Core;

class Application
{
    public function run(): void
    {
        $route = Routing\Router::find($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

        if ($route)
        {
            $action = $route->action;
            $action(...$route->getVariables());
            return;
        }

        http_response_code(404);
        echo 'Page not found';
    }
}