<?php

return [
	'APP_LANG' => 'ru',
	'DB_HOST' => '',
	'DB_USER' => '',
	'DB_PASSWORD' => '',
	'DB_NAME' => '',
	'CATEGORY_BLACK_LIST' => ['image', 'items_category', 'migration', 'status'],
	'FIELDS_STOP_LIST' => ['manufacturer_id', 'speed', 'material_id', 'color_id', 'target_id', 'password'],
	'DICTIONARY' => [
		'role' => 'Роли',
		'item' => 'Товары',
		'category' => 'Категория',
		'color' => 'Цвет',
		'manufacturer' => 'Производитель',
		'material' => 'Материал',
		'orders' => 'Заказы',
		'target_audience' => 'Целевая аудитория',
		'user' => 'Пользователь',
	],
	'DB_CHARACTERS' => [
		'int' => 'int',
		'decimal' => 'int',
		'varchar' => 'string',
		'char' => 'string'
	],
	'PRODUCT_LIMIT' => 9,
	'TEXT_TRUNCATE' => 150,
	'IMAGE_MAIN_HEIGHT' => 200,
	'IMAGE_MAIN_WIDTH' => 350,
	'IMAGE_DETAIL_HEIGHT' => 380,
	'IMAGE_DETAIL_WIDTH' => 650,
	'IMAGE_ALLOWED_TYPES' => ['image/jpg', 'image/png', 'image/bmp', 'image/jpeg', 'image/webp'],
	'IMAGE_MAX_SIZE' => 20971520,
];