<?php

use Core\Routing\Router;

Router::get('/category/:categoryName', [new App\Controller\IndexController(), 'showIndexPage']);

Router::get('/color/:colorName', [new App\Controller\IndexController(), 'showIndexPage']);

Router::get('/image/:imageName', [new App\Controller\IndexController(), 'showIndexPage']);

Router::get('/item/:itemName', [new App\Controller\IndexController(), 'showIndexPage']);

Router::get('/items_category/:items_categoryName', [new App\Controller\IndexController(), 'showIndexPage']);

Router::get('/manufacturer/:manufacturerName', [new App\Controller\IndexController(), 'showIndexPage']);

Router::get('/material/:materialName', [new App\Controller\IndexController(), 'showIndexPage']);

Router::get('/migration/:migrationName', [new App\Controller\IndexController(), 'showIndexPage']);

Router::get('/orders/:ordersName', [new App\Controller\IndexController(), 'showIndexPage']);

Router::get('/role/:roleName', [new App\Controller\IndexController(), 'showIndexPage']);

Router::get('/status/:statusName', [new App\Controller\IndexController(), 'showIndexPage']);

Router::get('/target_audience/:target_audienceName', [new App\Controller\IndexController(), 'showIndexPage']);

Router::get('/user/:userName', [new App\Controller\IndexController(), 'showIndexPage']);