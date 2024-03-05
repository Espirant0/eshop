<?php

namespace Core\Database\Repo;

use App\Cache\FileCache;
use App\Model\Bicycle;
use App\Model\Category;
use App\Service\DBHandler;
use Core\Database\ORM\QueryBuilder;

class DetailRepo
{
	public static function getBicycleById(int $id): Bicycle
	{
		$DBOperator = DBHandler::getInstance();
		$result = $DBOperator->query(QueryBuilder::
		select('id, title, create_year, price, description, status, speed', 'item')
			->join('name, engName', 'color')
			->join('name, engName', 'material')
			->join('name', 'manufacturer')
			->join('name, engName', 'target_audience', 'target_audience.id = item.target_id')
			->join('category_id', 'items_category')
			->join('name, engName', 'category')
			->where("item.id = $id")
			->where('item.status = 1')
			->as(
				[
					'color.name',
					'color.engName',
					'material.name',
					'material.engName',
					'manufacturer.name',
					'target_audience.name',
					'target_audience.engName',
					'category.name',
					'category.engName',
				],
				[
					'color',
					'color_engName',
					'material',
					'material_engName',
					'vendor',
					'target',
					'target_engName',
					'category_name',
					'category_engName',
				]
			)
		);

		if (!$result)
		{
			throw new \Exception($DBOperator->connect_error);
		}
		if ($result->num_rows < 1)
		{
			throw new \Exception('No ID', -1);
		}

		$row = mysqli_fetch_assoc($result);
		$categoryList = [new Category('category', $row['category_name'], $row['category_engName']),
			new Category('vendor', $row['vendor'], $row['vendor']),
			new Category('color', $row['color'], $row['color_engName']),
			new Category('material', $row['material'], $row['material_engName']),
			new Category('target', $row['target'], $row['target_engName'])];

		$bicycle = new Bicycle(
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
			$categoryList,
			$row['target']
		);
		$itemCache = new FileCache();
		$itemCache->set('bicycle', $bicycle, 3600);
		return $bicycle;
	}
}