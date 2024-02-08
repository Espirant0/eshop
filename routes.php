<?php

use Core\Routing\Router;

Router::get('/', [new App\Controller\IndexController(), 'showIndexPage']);

Router::get('/category_name', [new App\Controller\IndexController(), 'showIndexPage']);

Router::get('/Detail/ID', [new App\Controller\DetailController(), 'showDetailPage']);

Router::get('/OrderPage/order', [new App\Controller\OrderController(), 'showOrderPage']);

Router::get('/order/confirm', [new App\Controller\OrderController(), 'showConfirmedOrderPage'] );

Router::get('/auth', [new App\Controller\AuthController(), 'showAuthPage'] );

Router::post('/login', [new App\Controller\AuthController(), 'userLogin']);

Router::get('/sign_out', [new App\Controller\AuthController(), 'signOut']);

Router::get('/error', [new App\Controller\ErrorController(), 'showErrorPage'] );

Router::get('/admin_panel', [new App\Controller\AdminController(), 'checkAuth'] );

Router::get('/edit', [new App\Controller\EditFormController(), 'showEditFormPage']);

Router::post('/update', [new App\Controller\EditFormController(), 'updateValue']);

Router::get('/delete', [new App\Controller\AdminController(), 'deleteItem']);

Router::get('/add_form', [new App\Controller\EditFormController(), 'showAddFormPage']);

Router::post('/add', [new App\Controller\EditFormController(), 'addItem']);