<?php

use Up\Controller\DetailController;
use Up\Controller\IndexController;
use Up\Controller\OrderController;
use Up\Routing\Router;

Router::get('/', [new IndexController(), 'showIndexPage']);

Router::get('/category_name', function()
{
    echo '/public/category_name';
});

Router::get('/detail/good_id', [new DetailController(), 'showDetailPage']);

Router::get('/order', [new OrderController(), 'showOrderPage']);

Router::get('/order/confirm', [new OrderController(), 'showConfirmedOrderPage'] );