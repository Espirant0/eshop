<?php

namespace Core\Database\Repo;

use App\Cache\FileCache;
use App\Config\Config;
use App\Model\Bicycle;
use App\Service\DBHandler;
use App\Service\ImageHandler;

class AdminPanelRepo extends BaseRepo
{
	public static function addItem(
		string $title,
		string $category,
		string $createYear,
		string $price,
		string $description,
		string $status,
		string $manufacturerId,
		string $materialId,
		string $colorId,
	): void
	{
		$DBOperator = DBHandler::getInstance();
		$title = mysqli_real_escape_string($DBOperator, $title);
		$category = mysqli_real_escape_string($DBOperator,$category);
		$colorId = (int)mysqli_real_escape_string($DBOperator, $colorId);
		$createYear = (int)mysqli_real_escape_string($DBOperator, $createYear);
		$materialId = (int)mysqli_real_escape_string($DBOperator, $materialId);
		$description = mysqli_real_escape_string($DBOperator, $description);
		$price = (int)mysqli_real_escape_string($DBOperator, $price);
		$manufacturerId = (int)mysqli_real_escape_string($DBOperator, $manufacturerId);
		$status = (int)mysqli_real_escape_string($DBOperator, $status);

		$DBOperator->query("INSERT INTO item (title, create_year, price, description, status, manufacturer_id,material_id, color_id)
			                VALUES ('$title', $createYear, $price, '$description', $status, $manufacturerId, $materialId,$colorId)");

		$lastAddedId = $DBOperator->query('SELECT LAST_INSERT_ID()')->fetch_row()[0];

		$DBOperator->query("INSERT INTO items_category(item_id, category_id) VALUES ($lastAddedId,$category)");
		ImageHandler::createNewItemImage($lastAddedId, $title);
		$DBOperator->query("INSERT INTO image (item_id, is_main, ord) VALUES ('$lastAddedId',1,1)");
	}
	public static function updateItem(string $table, int $itemId, array $newValues):void
	{
		$DBOperator = DBHandler::getInstance();
		$table = mysqli_real_escape_string($DBOperator,$table);
		$expression = '';
		foreach ($newValues as $key => $value) {
			$newValues[$key] = mysqli_real_escape_string($DBOperator, $value);
			$expression.= ' '.$key.' = '."'$newValues[$key]'".', ';
		}
		$expression = rtrim($expression, ', ');
		var_dump($expression);
		$DBOperator->query("SET FOREIGN_KEY_CHECKS = 0;");
		$DBOperator->query("
							UPDATE $table 
							SET $expression 
							WHERE id = $itemId
							");
		FileCache::deleteCacheByKey('categoriesWithoutEmptyCategory');
	}

	public static function deleteBicycle(int $itemId):void
	{
		$DBOperator = DBHandler::getInstance();
		$DBOperator->query("UPDATE item SET item.status = 0 WHERE item.id = '$itemId'");
		FileCache::deleteCacheByKey('categoriesWithoutEmptyCategory');
	}

	public static function checkItemColumns(string $table, string $field, mixed $value):bool
	{
		$DBOperator = DBHandler::getInstance();
		$table = strtoupper(mysqli_real_escape_string($DBOperator,$table));
		$fields = [];
		$value = (ctype_digit($value))? 'int': 'varchar';
		$result = $DBOperator->query("SHOW COLUMNS FROM $table");
		while ($row = mysqli_fetch_assoc($result))
		{
			$fields[] = [$row['Field'] => current(explode('(',$row['Type']))];
		}

		return in_array([$field => $value], $fields, true);
	}

	public static function getItemColumns(string $table):array
	{
		if($table === '')
		{
			return [];
		}
		$DBOperator = DBHandler::getInstance();
		$table = mysqli_real_escape_string($DBOperator,$table);
		$fields = [];
		$result = $DBOperator->query("SHOW COLUMNS FROM $table");
		while ($row = mysqli_fetch_assoc($result))
		{
			$fields[] = $row['Field'];
		}
		return $fields;
	}
	public static function getItemList(string $item, int $currentPage = 1): array
	{
		if($item === '')
		{
			return [];
		}
		$config = new Config();
		$itemsPerPage = $config->option('PRODUCT_LIMIT');
		$startId = ($currentPage - 1) * $itemsPerPage;
		$DBOperator = DBHandler::getInstance();
		$item = mysqli_real_escape_string($DBOperator, $item);
		$itemFields = self::getItemColumns($item);
		$queryFields = implode(' ,', $itemFields);
		$result = $DBOperator->query("
				SELECT {$queryFields} 
				FROM {$item} 
				WHERE id IN(SELECT id FROM {$item} WHERE id > $startId)
				ORDER BY id
				LIMIT $itemsPerPage;
		");
		if (!$result) {
			throw new \Exception($DBOperator->connect_error);
		}
		return mysqli_fetch_all($result);
	}
}
