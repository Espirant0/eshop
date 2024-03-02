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
$quer = QueryBuilder::select('id, title, create_year, price, description, status, speed', 'item')
	->join('name', 'manufacturer')
	->join('name, engName', 'color')
	->join('name, engName', 'material')
	->join('name, engName', 'target_audience','target_audience.id = item.target_id')
	->join('category_id', 'items_category')
	->join('name, engName', 'category')->where("item.id = 1 AND item.status = 1")
	->as('color.name', 'color')->as('color.engName', 'color_engname')
	->as('material.name', 'material')->as('material.engName', 'material_engname')
	->as('manufacturer.name', 'vendor')->as('target_audience.name', 'target')
	->as('target_audience.engName', 'target_engname')->as('category.engName', 'category_engname')
	->as('category.name', 'category_name')->getQuery();
echo $quer;
#var_dump(DBHandler::getInstance()->query($quer));
DBHandler::getInstance()->query('SET foreign_key_checks = 1');