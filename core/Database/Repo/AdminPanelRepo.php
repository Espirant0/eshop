<?php

namespace Core\Database\Repo;

use App\Model\User;
use App\Model\Bicycle;
use App\Service\DBHandler;

class AdminPanelRepo extends BaseRepo
{
	public static function getItemList(): array
	{
		$DBOperator = new DBHandler();
		$itemList = [];
		$result = $DBOperator->query("
		SELECT i.id, i.title, c.name as color, i.create_year, m2.name as material, i.price, i.description, i.status, m.name as vendor 
		FROM item i
		        INNER JOIN manufacturer m on m.id = i.manufacturer_id
				INNER JOIN color c on i.color_id = c.id
				INNER JOIN material m2 on i.material_id = m2.id
		");
		if (!$result) {
			throw new \Exception($DBOperator->connect_error);
		}
		while ($row = mysqli_fetch_assoc($result))
		{
			$itemId = $row['id'];
			$itemName = $row['title'];
			$itemColor = $row['color'];
			$itemYear = $row['create_year'];
			$itemMaterial = $row['material'];
			$itemPrice = $row['price'];
			$itemDescription = $row['description'];
			$itemStatus = $row['status'];
			$itemManufacturer = $row['vendor'];

			$itemList[] = new Bicycle($itemId, $itemName, $itemColor, $itemYear,$itemMaterial,$itemPrice,$itemDescription, $itemStatus,$itemManufacturer);
		}
		return $itemList;
	}
}
