<?php

namespace Core\Database\Repo;
use App\Cache\FileCache;
use App\Model\Bicycle;
use App\Model\Category;
use App\Service\DBHandler;
use Core\Database\ORM\QueryBuilder;

class DetailRepo extends BaseRepo
{
	public static function getBicycleById(int $id): Bicycle
	{
		$DBOperator = DBHandler::getInstance();
		$result = $DBOperator->query(QueryBuilder::
			select('id, title, create_year, price, description, status, speed','item')
			->join('name, engName','color')
			->join('name, engName','material')
			->join('name','manufacturer')
			->join('name, engName','target_audience', 'target_audience.id = item.target_id')
			->join('category_id','items_category')
			->join('name, engName', 'category')
			->where("item.id = $id")
			->where('item.status = 1')
			->as(['color.name','color.engName','material.name','material.engName','manufacturer.name','target_audience.name','target_audience.engName','category.name','category.engName'],['color','color_engname','material','material_engname','vendor','target','target_engname','category_name','category_engname'])
			);

		if (!$result)
		{
			throw new \Exception($DBOperator->connect_error);
		}
		if($result->num_rows<1)
		{
			throw new \Exception('No ID',-1);
		}

		$row = mysqli_fetch_assoc($result);
		$itemId = $row['id'];
		$itemName = $row['title'];
		$itemColor = $row['color'];
		$itemYear = $row['create_year'];
		$itemMaterial = $row['material'];
		$itemPrice = $row['price'];
		$itemDescription = $row['description'];
		$itemStatus = $row['status'];
		$itemSpeed = $row['speed'];
		$itemManufacturer = $row['vendor'];
		$itemTarget = $row['target'];
		$categoryList = [new Category('category',$row['category_name'],$row['category_engname']),
			new Category('vendor',$row['vendor'],$row['vendor']),
			new Category('color',$row['color'],$row['color_engname']),
			new Category('material',$row['material'],$row['material_engname']),
			new Category('target',$row['target'], $row['target_engname'])];

		$bicycle = new Bicycle($itemId, $itemName, $itemColor, $itemYear,$itemMaterial,$itemPrice,$itemDescription, $itemStatus,$itemManufacturer,$itemSpeed,$categoryList, $itemTarget);
		$itemCache = new FileCache();
		$itemCache->set('bicycle', $bicycle, 3600);
		return $bicycle;
	}
}