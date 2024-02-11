<?php

namespace Core\Database\Repo;
use App\Model\Bicycle;
use App\Service\DBHandler;
use App\Service\CategoryListRepo;

class DetailRepo extends BaseRepo
{
	public static function getBicycleListById(int $id): Bicycle
	{
		$DBOperator = new DBHandler();
		$result = $DBOperator->query(
			"SELECT i.id, i.title, i.create_year, i.price, i.description, i.status, i.speed, c.name as color, ma.name as material, m.name as vendor, ta.name as target, c2.engName as category_engname, ic.category_id, c2.name as category_name
		FROM item i
		INNER JOIN manufacturer m on m.id = i.manufacturer_id
		INNER JOIN color c on c.id = i.color_id
		INNER JOIN material ma on ma.id = i.material_id
		INNER JOIN target_audience ta on ta.id = i.target_id
		INNER JOIN items_category ic on i.id = ic.item_id
		INNER JOIN category c2 on ic.category_id = c2.id
		WHERE i.id = '{$id}';
		");

		if (!$result)
		{
			throw new \Exception($DBOperator->connect_error);
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
		$category = [$row['category_name'],$row['vendor'],$row['color'],$row['material']];

		return new Bicycle($itemId, $itemName, $itemColor, $itemYear,$itemMaterial,$itemPrice,$itemDescription, $itemStatus,$itemManufacturer,$itemSpeed,$category);
	}
}

