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
		$itemQuery = $DBOperator->query("
		SELECT i.id, i.title, c.name as color, i.create_year, m2.name as material, i.price, i.description, i.status, m.name as vendor 
		FROM item i
		        INNER JOIN manufacturer m on m.id = i.manufacturer_id
				INNER JOIN color c on i.color_id = c.id
				INNER JOIN material m2 on i.material_id = m2.id
		
		ORDER BY i.id;
		");
		if (!$itemQuery) {
			throw new \Exception($DBOperator->connect_error);
		}

		while ($row = mysqli_fetch_assoc($itemQuery))
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
	public static function addItem(string $title,
								   string $createYear,
								   string $price,
								   string $description,
								   string $status,
								   string $manufacturerId,
								   string $materialId,
								   string $colorId,
	):void{
		$DBOperator = new DBHandler();
		$title = mysqli_real_escape_string($DBOperator,$title);
		$colorId = (int)mysqli_real_escape_string($DBOperator,$colorId);
		$createYear = (int)mysqli_real_escape_string($DBOperator,$createYear);
		$materialId = (int)mysqli_real_escape_string($DBOperator,$materialId);
		$description = mysqli_real_escape_string($DBOperator,$description);
		$price = (int)mysqli_real_escape_string($DBOperator,$price);
		$manufacturerId = (int)mysqli_real_escape_string($DBOperator,$manufacturerId);
		$status = (int)mysqli_real_escape_string($DBOperator,$status);
		$DBOperator->query("INSERT INTO item (title, create_year, price, description, status, manufacturer_id,material_id, color_id)
			VALUES ('$title', $createYear, $price, '$description', $status, $manufacturerId, $materialId,$colorId)");
	}
	public static function updateItem(int $itemId, string $field, string $newValue):void
	{
		$DBOperator = new DBHandler();
		$field = mysqli_real_escape_string($DBOperator,$field);
		$newValue = mysqli_real_escape_string($DBOperator,$newValue);
		$DBOperator->query("UPDATE item SET $field = '$newValue' WHERE item.id = '$itemId'");
	}

	public static function deleteItem(int $itemId):void
	{
		$DBOperator = new DBHandler();
		$DBOperator->query("SET FOREIGN_KEY_CHECKS=0;");
		$DBOperator->query("DELETE FROM item WHERE item.id = '$itemId'");
	}

	public static function checkItemColumns(string $field, string $value):bool
	{
		$DBOperator = new DBHandler();
		$fields = [];
		$value = (ctype_digit($value))? 'int': 'varchar';
		$result = $DBOperator->query('SHOW COLUMNS FROM ITEM');
		while ($row = mysqli_fetch_assoc($result))
		{
			$fields[] = [$row['Field'] => current(explode('(',$row['Type']))];
		}
		return in_array([$field => $value], $fields, true);
	}
}


