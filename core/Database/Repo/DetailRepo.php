<?php

namespace Core\Database\Repo;
use App\Cache\FileCache;
use App\Model\Bicycle;
use App\Model\Category;
use App\Service\DBHandler;

class DetailRepo extends BaseRepo
{
	public static function getBicycleById(int $id): Bicycle
	{
		$DBOperator = DBHandler::getInstance();
		$result = $DBOperator->query(
			"SELECT i.id, i.title, i.create_year, i.price, i.description, i.status, i.speed, c.name as color,c.engName as color_engname, ma.name as material, ma.engName as material_engname, m.name as vendor, ta.name as target, ta.engName as target_engname, c2.engName as category_engname, ic.category_id, c2.name as category_name
		FROM item i
		INNER JOIN manufacturer m on m.id = i.manufacturer_id
		INNER JOIN color c on c.id = i.color_id
		INNER JOIN material ma on ma.id = i.material_id
		INNER JOIN target_audience ta on ta.id = i.target_id
		INNER JOIN items_category ic on i.id = ic.item_id
		INNER JOIN category c2 on ic.category_id = c2.id
		WHERE i.id = '{$id}' AND i.status = 1;
		");

		if (!$result)
		{
			throw new \Exception($DBOperator->connect_error);
		}
		if($result->num_rows<1) throw new \Exception('No ID',-1);

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