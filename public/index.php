<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/../boot.php';

$migrator = new Core\Database\Migration\Migrator();
$migrator::deleteData();

$App = new Core\Application();
$App->run();