<?php

use Core\Routing\Router;

Router::get('/', [new App\Controller\IndexController(), 'showIndexPage']);

Router::get('/category_name', [new App\Controller\IndexController(), 'showIndexPage']);

Router::get('/detail/good_id', [new App\Controller\DetailController(), 'showDetailPage']);

Router::get('/order', [new App\Controller\OrderController(), 'showOrderPage']);

Router::get('/order/confirm', [new App\Controller\OrderController(), 'showConfirmedOrderPage'] );