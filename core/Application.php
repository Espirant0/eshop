<?php

namespace Up\Routing;

class Application
{
    public function run()
    {
        $route = Router::find($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

        if ($route)
        {
            $action = $route->action;
            $variables = $route->getVariables();
            echo $action(...$variables);
        }
        else
        {
            http_response_code(404);
            echo 'Page not found';
            exit;
        }
    }
}