<?php

use Core\Routing\Router;

//Публичная часть

Router::get('/', [new App\Controller\IndexController(), 'showIndexPage']);

Router::get('/category/:categoryName', [new App\Controller\IndexController(), "showIndexPage"]);

Router::get('/detail/:itemId', [new App\Controller\DetailController(), 'showDetailPage']);

Router::get('/order/:itemId', [new App\Controller\OrderController(), 'showOrderPage']);

Router::post('/order/confirm/:itemId', [new App\Controller\OrderController(), 'saveOrder'] );

Router::get('/confirmed', [new App\Controller\OrderController(), 'showConfirmedOrderPage']);

Router::get('/error', [new App\Controller\ErrorController(), 'showErrorPage'] );

//Авторизация

Router::get('/auth', [new App\Controller\AuthController(), 'showAuthPage'] );

Router::post('/login', [new App\Controller\AuthController(), 'userLogin']);

Router::get('/sign_out', [new App\Controller\AuthController(), 'signOut']);

//Административная часть

Router::get('/admin_panel', [new App\Controller\AdminController(), 'showAdminPage'] );

Router::get('/admin_panel/edit', [new App\Controller\EditFormController(), 'showEditFormPage']);

Router::post('/admin_panel/update', [new App\Controller\EditFormController(), 'updateValue']);

Router::get('/admin_panel/delete', [new App\Controller\AdminController(), 'deleteBicycle']);

Router::get('/admin_panel/add_form', [new App\Controller\EditFormController(), 'showAddFormPage']);

Router::post('/admin_panel/add', [new App\Controller\EditFormController(), 'addItem']);