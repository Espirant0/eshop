<?php

namespace Core\Database\Repo;

use App\Cache\FileCache;
use App\Config\Config;
use App\Model\Bicycle;
use App\Service\DBHandler;
use App\Service\ImageHandler;
use Core\Database\ORM\QueryBuilder;

class AdminPanelRepo extends BaseRepo
{
	public static function addItem(Bicycle $bicycle, array $images): void
	{
		$itemId = $bicycle->getId();
		$title = $bicycle->getName();
		QueryBuilder::insert('item','title, create_year, price, description, status, manufacturer_id, speed, material_id, color_id, target_id',
			"{$bicycle->getName()},{$bicycle->getYear()},{$bicycle->getPrice()},{$bicycle->getDescription()},{$bicycle->getStatus()},{$bicycle->getVendor()},{$bicycle->getSpeed()},{$bicycle->getMaterial()},{$bicycle->getColor()}, {$bicycle->getTarget()}");

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
				QueryBuilder::insert('image', 'item_id, is_main, ord', "$itemId, $isMain,$number");
				$number++;
			}
		}
	}

	public static function getLastFreeId(): int
	{
		$DBOperator = DBHandler::getInstance();
		return ($DBOperator->query(QueryBuilder::select('id','item')->orderBy('item.id',QueryBuilder::DESCENDING,1))->fetch_row()[0] + 1);
	}

	public static function updateItem(string $table, int $itemId, array $newValues): void
	{
		$DBOperator = DBHandler::getInstance();
		$table = mysqli_real_escape_string($DBOperator, $table);
		foreach ($newValues as $key => $value)
		{
			QueryBuilder::update("$table","$key",[$value],"id = $itemId");
		}
		FileCache::deleteCacheByKey('categoriesWithoutEmptyCategory');
	}

	public static function deleteBicycle(int $itemId): void
	{
		QueryBuilder::update('item','status',0,"item.id = $itemId");
		FileCache::deleteCacheByKey('categoriesWithoutEmptyCategory');
	}

	public static function getItemColumns(string $table): array
	{
		if ($table === '')
		{
			return [];
		}
		$config = Config::getInstance();
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
		if (isset($currentPage))
		{
			$config = Config::getInstance();
			$itemsPerPage = $config->option('PRODUCT_LIMIT');
			$startId = ($currentPage - 1) * $itemsPerPage;
		}
		$itemList = [];
		$DBOperator = DBHandler::getInstance();
		$item = mysqli_real_escape_string($DBOperator, $item);
		$itemFields = self::getItemColumns($item);
		$queryFields = implode(', ', $itemFields);
		$result = $DBOperator->query(QueryBuilder::
			select("$queryFields","$item")
			->where("$item.id",QueryBuilder::select('id',"$item")->where("$item.id > $startId"))
			->orderBy("$item.id",limit:$itemsPerPage)
		);
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
		$config = Config::getInstance();

		$pagesCount = ceil($itemId / $config->option('PRODUCT_LIMIT'));

		$itemList = self::getItemList($table, $pagesCount);

		return $itemList[$itemId - 1 - $config->option('PRODUCT_LIMIT') * ($pagesCount - 1)];
	}
}