<?php

namespace Core;

use App\Controller\PageNotFoundController;
<<<<<<< 9252b2df7ddb4817d9b0581439daee939416f397
use App\Service\HttpService;
=======
use App\Service\ExceptionHandler;
use App\Service\RoutesGenerator;
>>>>>>> d49067f5b409095f343afafe487153843b2b1223
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
		if (str_contains($_SERVER['REQUEST_URI'],'admin_panel'))
		{
			HttpService::redirect('admin_panel');
			return;
		}

        http_response_code(404);
        $err=new PageNotFoundController();
		$err->PageNotFoundViewer();
    }
}