<?php

use Core\Routing\Router;

//Публичная часть
Router::get('/', [new App\Controller\IndexController(), 'showIndexPage']);

Router::get('/category/:categoryName/', [new App\Controller\IndexController(), "showIndexPage"]);

Router::get('/detail/:itemId', [new App\Controller\DetailController(), 'showDetailPage']);

Router::get('/order', [new App\Controller\OrderController(), 'showOrderPage']);

Router::post('/order/confirm', [new App\Controller\OrderController(), 'saveOrder'] );

Router::get('/confirmed', [new App\Controller\OrderController(), 'showConfirmedOrderPage']);

Router::get('/error', [new App\Controller\ErrorController(), 'showErrorPage'] );

//Авторизация

Router::get('/auth', [new App\Controller\AuthController(), 'showAuthPage'] );

Router::post('/login', [new App\Controller\AuthController(), 'userLogin']);

Router::get('/sign_out', [new App\Controller\AuthController(), 'signOut']);

//Административная часть

Router::get('/admin_panel', [new App\Controller\AdminController(), 'showAdminPage'] );

Router::get('/admin_panel/', [new App\Controller\AdminController(), 'showAdminPage'] );

Router::get('/admin_panel/:tableName/', [new App\Controller\AdminController(), 'showAdminPage'] );

Router::get('/admin_panel/:tableName/edit', [new App\Controller\EditFormController(), 'showEditFormPage']);

Router::post('/admin_panel/:tableName/update', [new App\Controller\EditFormController(), 'updateValue']);

<<<<<<< 9252b2df7ddb4817d9b0581439daee939416f397
Router::get('/admin_panel/:tableName/delete', [new App\Controller\AdminController(), 'deleteBicycle']);

Router::get('/admin_panel/:tableName/add_form', [new App\Controller\EditFormController(), 'showAddFormPage']);

Router::post('/admin_panel/:tableName/add', [new App\Controller\EditFormController(), 'addItem']);

Router::get('/admin_panel/dev_reset', [new App\Controller\AdminController(), 'resetData']);
=======
Router::post('/admin_panel/add', [new App\Controller\EditFormController(), 'addItem']);

require_once 'routesForFiltration.php';
>>>>>>> d49067f5b409095f343afafe487153843b2b1223