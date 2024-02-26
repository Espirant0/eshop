<?php

namespace Core\Database\Repo;

use App\Cache\FileCache;
use App\Config\Config;
use App\Model\Bicycle;
use App\Service\DBHandler;
use App\Service\ImageHandler;

class AdminPanelRepo extends BaseRepo
{
	public static function addItem(Bicycle $bicycle, array $images): void
	{
		$DBOperator = DBHandler::getInstance();

		$itemId = $bicycle->getId();
		$title = $bicycle->getName();
		$year = $bicycle->getYear();
		$price = $bicycle->getPrice();
		$description = $bicycle->getDescription();
		$status = $bicycle->getStatus();
		$manufacturerId = $bicycle->getVendor();
		$materialId = $bicycle->getMaterial();
		$colorId = $bicycle->getColor();
		$category = $bicycle->getCategories()[0];
		$speed = $bicycle->getSpeed();

		$DBOperator->query("INSERT INTO item (title, create_year, price, description, status, manufacturer_id, speed, material_id, color_id)
			                VALUES ('$title', $year, $price, '$description', $status, $manufacturerId, $speed, $materialId,$colorId)");

		$DBOperator->query("INSERT INTO items_category(item_id, category_id) VALUES ($itemId,'$category')");

		if (empty($images))
		{
			ImageHandler::createNewItemDefaultImage($itemId, $title);
		} else
		{
			$number = 1;
			foreach ($images['tmp_name'] as $image)
			{
				ImageHandler::createNewItemImage($image, $itemId, $title, $number);
				if ($number === 1)
				{
					$isMain = 1;
				} else
				{
					$isMain = 0;
				}
				$DBOperator->query("INSERT INTO image (item_id, is_main, ord) VALUES ('$itemId',$isMain,$number)");
				$number++;
			}
		}
	}

	public static function getLastFreeId(): int
	{
		$DBOperator = DBHandler::getInstance();
		return ($DBOperator->query('SELECT id FROM item ORDER BY id DESC LIMIT 1')->fetch_row()[0] + 1);
	}

	public static function updateItem(string $table, int $itemId, array $newValues): void
	{
		$DBOperator = DBHandler::getInstance();
		$table = mysqli_real_escape_string($DBOperator, $table);
		$expression = '';
		foreach ($newValues as $key => $value)
		{
			$newValues[$key] = mysqli_real_escape_string($DBOperator, $value);
			$expression .= ' ' . $key . ' = ' . "'$newValues[$key]'" . ', ';
		}
		$expression = rtrim($expression, ', ');
		$DBOperator->query("SET FOREIGN_KEY_CHECKS = 0;");
		$DBOperator->query("
							UPDATE $table 
							SET $expression 
							WHERE id = $itemId
							");
		FileCache::deleteCacheByKey('categoriesWithoutEmptyCategory');
	}

	public static function deleteBicycle(int $itemId): void
	{
		$DBOperator = DBHandler::getInstance();
		$DBOperator->query("UPDATE item SET item.status = 0 WHERE item.id = '$itemId'");
		FileCache::deleteCacheByKey('categoriesWithoutEmptyCategory');
	}

	public static function getItemColumns(string $table): array
	{
		if ($table === '')
		{
			return [];
		}
		$config = new Config();
		$ignoredFields = $config->option('FIELDS_STOP_LIST');
		$DBOperator = DBHandler::getInstance();
		$table = mysqli_real_escape_string($DBOperator, $table);
		$fields = [];
		$result = $DBOperator->query("SHOW COLUMNS FROM $table");
		while ($row = mysqli_fetch_assoc($result))
		{
			if (!in_array($row['Field'], $ignoredFields))
			{
				$fields[] = $row['Field'];
			}
		}
		return $fields;
	}

	public static function getItemList(string $item, ?int $currentPage = 1): array
	{
		if ($item === '')
		{
			return [];
		}
		$limit = '';
		if (isset($currentPage))
		{
			$config = new Config();
			$itemsPerPage = $config->option('PRODUCT_LIMIT');
			$startId = ($currentPage - 1) * $itemsPerPage;
			$limit = "LIMIT {$itemsPerPage}";
		}
		$itemList = [];
		$DBOperator = DBHandler::getInstance();
		$item = mysqli_real_escape_string($DBOperator, $item);
		$itemFields = self::getItemColumns($item);
		$queryFields = implode(' ,', $itemFields);
		$result = $DBOperator->query("
				SELECT {$queryFields} 
				FROM {$item} 
				WHERE id IN(SELECT id FROM {$item} WHERE id > $startId)
				ORDER BY id
				$limit;
		");
		if (!$result)
		{
			throw new \Exception($DBOperator->connect_error);
		}
		while ($row = mysqli_fetch_assoc($result))
		{
			$itemList[] = $row;
		}
		return $itemList;
	}

	public static function getItemById(string $table, int $itemId): array
	{
		$config = new Config();

		$pagesCount = ceil($itemId / $config->option('PRODUCT_LIMIT'));

		$itemList = self::getItemList($table, $pagesCount);

		return $itemList[$itemId - 1 - $config->option('PRODUCT_LIMIT') * ($pagesCount - 1)];
	}
}