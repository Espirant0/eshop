<?php

namespace Core\Database\Repo;

use App\Model\User;
use App\Model\Bicycle;

class AdminPanelRepo extends BaseRepo
{
	public static function getItemList(): array
	{
		$connection = BaseRepo::getDbConnection();
		$itemList = [];
		$result = mysqli_query($connection, "
		SELECT i.id, i.title, i.color, i.create_year, i.material, i.price, i.description, i.status, m.name as vendor 
		FROM item i
		         INNER JOIN manufacturer m on m.id = i.manufacturer_id
		");
		if (!$result) {
			throw new Exception(mysqli_error($connection));
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
