<?php

const ROOT = __DIR__;
require_once ROOT . '/vendor/autoload.php';
require_once ROOT . '/config/config.php';
require_once ROOT . '/routes.php';

use App\Service\DBHandler;
use Core\Database\ORM\QueryBuilder;

/*
#var_dump(QueryBuilder::getTableRestrictions('item'));
#echo QueryBuilder::insert('material','name, engName','пластик, plastic');
#QueryBuilder::update('item','title, color_id','newTitle, 3',1);
#var_dump(QueryBuilder::select('all','item')->aggregate('item.id',SUM)->getQuery());
#echo QueryBuilder::select('all','item')->aggregate('item.id',SUM)->getQuery();

var_dump(QueryBuilder::getTableRestrictions('item'));
#QueryBuilder::insert('color','engName, name', '123, asd');
\App\Service\ClearTestData::clear();
//var_dump(QueryBuilder::select('id','item')->join('name', 'manufacturer')->where('item.id > 10')->getQuery());
*/
DBHandler::getInstance()->query('SET foreign_key_checks = 0');
QueryBuilder::insert('orders','item_id, status_id, data_create, price, user_id, address',"1, 1, 2024-02-13, 1231, 799999, улица пушкина дом колотушкина");
#echo $quer. "\n";
#var_dump(DBHandler::getInstance()->query($quer));
DBHandler::getInstance()->query('SET foreign_key_checks = 1');