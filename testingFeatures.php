<?php

const ROOT = __DIR__;

require_once ROOT . '/vendor/autoload.php';
require_once ROOT . '/config/config.php';
require_once ROOT . '/routes.php';

use App\Service\DBHandler;
$newItem = [];
$item = 'item';
$DBOperator = DBHandler::getInstance();
$item = mysqli_real_escape_string($DBOperator, $item);
$itemFields = \Core\Database\Repo\AdminPanelRepo::getItemColumns($item);
$queryFields = implode(' ,', $itemFields);
$result = $DBOperator->query("
				SELECT {$queryFields} 
				FROM {$item} 
				ORDER BY id
		");
if (!$result) {
	throw new \Exception($DBOperator->connect_error);
}

while ($row = mysqli_fetch_assoc($result)) {
	$newItem[] = $row;
}
var_dump($newItem);
