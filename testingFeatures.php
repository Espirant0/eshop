<?php

const ROOT = __DIR__;
#ORM flag constants
const ASCENDING = 1;
const DESCENDING = 2;
const INNER = 1;
const LEFT = 2;
const RIGHT = 3;
const FULL = 4;
const CROSS = 5;
const AVERAGE = 1;
const COUNT = 2;
const SUM = 3;
const MIN =4;
const MAX = 5;
#end of ORM flag constants
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