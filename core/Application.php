<?php

namespace Core;

use App\Controller\PageNotFoundController;
use App\Service\ExceptionHandler;
use App\Service\RoutesGenerator;
use Core\Database\Migration\Migrator;

class Application
{
    public function run(): void
    {
		set_error_handler([ExceptionHandler::getInstance(),'errorToLogger']);
		set_exception_handler([ExceptionHandler::getInstance(),'exceptionToLogger']);
		$migration = new Migrator();
		$migration->migrate();

        $routesGenerator = new RoutesGenerator();
        $routesGenerator::addGenerateRoutesToCurrent();

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