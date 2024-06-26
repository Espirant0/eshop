<?php

namespace Core\Database\Repo;

use App\Config\Config;
use App\Model\Category;
use App\Service\DBHandler;
use App\Model\Bicycle;
use Core\Database\ORM\QueryBuilder;
use Exception;

class BicycleRepo
{
	public static function getBicycleList(int $currentPage, string $categoryName = '', ?array $property = [], int &$itemCount = null): array
	{
		$config = Config::getInstance();
		$itemsPerPage = $config->option('PRODUCT_LIMIT');
		$startId = ($currentPage - 1) * $itemsPerPage;
		if ($categoryName !== '')
		{
			$property['category'] = $categoryName;
		}
		if (isset($property))
		{
			return BicycleRepo::getFilteredBicycleList($currentPage, $property, $itemCount);
		}
		$query = QueryBuilder::
		select('id, title, create_year, price, description, status, speed', 'item')
			->join('engName', 'color')->join('name', 'manufacturer')
			->join('engName', 'material')
			->join('engName', 'target_audience', 'target_audience.id = item.target_id')
			->join('category_id', 'items_category')
			->join('name, engName', 'category')
			->where('item.status = 1')
			->where('item.id', QueryBuilder::select('id', 'item')->where("item.id > $startId"))
			->as(
				[
					'color.engName',
					'material.engName',
					'manufacturer.name',
					'target_audience.engName',
					'category.name',
					'category.engName',
				],
				[
					'color',
					'material',
					'vendor',
					'target',
					'category_name',
					'category',
				]
			)
			->orderBy('item.id', limit: $itemsPerPage);
		$DBOperator = DBHandler::getInstance();
		$result = $DBOperator->query($query);

		$bicycles = [];

		if (!$result)
		{
			throw new Exception($DBOperator->connect_error);
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$category[] = new Category(
				$row['category_id'],
				$row['category_name'],
				$row['category'],
			);

			$bicycles[] = new Bicycle
			(
				$row['id'],
				$row['title'],
				$row['color'],
				$row['create_year'],
				$row['material'],
				$row['price'],
				$row['description'],
				$row['status'],
				$row['vendor'],
				$row['speed'],
				$category,
				$row['target'],
			);
			unset($category);
		}
		return $bicycles;
	}

	public static function getFilteredBicycleList(int $currentPage, ?array $property = [], int &$itemCount = null): array
	{
		$config = Config::getInstance();
		$limit = $config->option('PRODUCT_LIMIT');
		if (isset($property))
		{
			$filter = $property;
			$property = null;
		}
		$filteredBicycleList = [];
		$DBOperator = DBHandler::getInstance();
		$query = QueryBuilder::
		select('id, title, create_year, price, description, status, speed', 'item')
			->join('engName', 'color')
			->join('name', 'manufacturer')
			->join('engName', 'material')
			->join('engName', 'target_audience', 'target_audience.id = item.target_id')
			->join('category_id', 'items_category')
			->join('name, engName', 'category')
			->where('item.status = 1');
		if (isset($filter))
		{
			foreach ($filter as $key => $value)
			{
				switch ($key)
				{
					case 'search':
						$query->where("item.title LIKE '%$value%'", custom: true);
						continue 2;
					case 'target':
						$key = 'target_audience';
						break;
					case 'vendor':
						$query = $query->where("manufacturer.name = '$value'");
						continue 2;
				}
				$query = $query->where("$key.engName = '$value'");
			}
		}
		$query
			->as(
				[
					'color.engName',
					'material.engName',
					'manufacturer.name',
					'target_audience.engName',
					'category.name',
					'category.engName',
				],
				[
					'color',
					'material',
					'vendor',
					'target',
					'category_name',
					'category',
				]
			)
			->orderBy('item.id');
		$result = $DBOperator->query($query);
		$itemCount = count($result->fetch_all());
		$pageCounter = 0;
		foreach ($result as $row)
		{
			$category[] = new Category(
				$row['category_id'],
				$row['category_name'],
				$row['category']
			);
			if (!is_null($property) && !str_contains(strtolower($row['title']), strtolower($property)))
			{
				continue;
			}
			$pageCounter++;
			if ($pageCounter > ($currentPage - 1) * $limit)
			{
				$filteredBicycleList[] = new Bicycle
				(
					$row['id'],
					$row['title'],
					$row['color'],
					$row['create_year'],
					$row['material'],
					$row['price'],
					$row['description'],
					$row['status'],
					$row['vendor'],
					$row['speed'],
					$category,
					$row['target'],
				);
			}
			if ($pageCounter == $currentPage * $limit) break;
		}
		return $filteredBicycleList;
	}
}