<?php

const ROOT = __DIR__;
const TITLE = 'Каталкин и Ко';
#ORM flag constants
const ASCENDING = 1;
const DESCENDING = 2;
const INNER = 1;
const LEFT = 2;
const RIGHT = 3;
const FULL = 4;
const CROSS = 5;
#end of ORM flag constants
require_once ROOT . '/vendor/autoload.php';
require_once ROOT . '/config/config.php';
require_once ROOT . '/routes.php';