<?php

namespace App\Service;

use App\Config\Config;
use App\Service\DBHandler;

class RoutesGenerator
{
    public static function generateRoutes(): array
    {
        $config = new Config();
        $DBOperator = DBHandler::getInstance();

        $tables = $DBOperator->query("SHOW TABLES FROM {$config->option('DB_NAME')};");

        $routes = [];

        foreach ($tables as $table)
        {
            $tableName = is_array($table) ? reset($table) : $table;
            $route = "/$tableName/:{$tableName}Name";
            $routes[] = $route;
        }

        return $routes;
    }

    public static function addGenerateRoutesToCurrent(): void
    {
        $generatedRoutes = self::generateRoutes();

        $currentRoutes = file_get_contents(ROOT . '/routesForFiltration.php');

        foreach ($generatedRoutes as $route)
        {
            if (!str_contains($currentRoutes, $route))
            {
                $currentRoutes .= "\nRouter::get('$route', [new App\Controller\IndexController(), 'showIndexPage']);\n";
            }
        }

        file_put_contents(ROOT . '/routesForFiltration.php', $currentRoutes);
    }
}