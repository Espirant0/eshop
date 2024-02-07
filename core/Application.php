<?php

namespace Core;

use App\Controller\PageNotFoundController;
use Core\Database\Migration\Migrator;

class Application
{
    public function run(): void
    {
		$migration = new Migrator();
		#Migrator::deleteData();
		$migration->migrate();

        $route = Routing\Router::find($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

        if ($route)
        {
            $action = $route->action;
            $action(...$route->getVariables());
            return;
        }

        http_response_code(404);
        $err=new PageNotFoundController();
		$err->PageNotFoundViewer();
    }
}