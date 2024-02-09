<?php

namespace Core\Database\Repo;

use App\Service\DBHandler;
use App\Model\Bicycle;




class BicycleRepo extends BaseRepo
{
	public static function getBicyclelist(): array
	{
		$DBOperator = new DBHandler();
		$result = $DBOperator->query(
			"SELECT i.id, i.title, i.create_year, i.price, i.description, i.status, c.name as color, ma.name as material, m.name as vendor, ta.name as target
		FROM item i
		INNER JOIN manufacturer m on m.id = i.manufacturer_id
		INNER JOIN color c on c.id = i.color_id
		INNER JOIN material ma on ma.id = i.material_id
		INNER JOIN target_audience ta on ta.id = i.target_id
		ORDER BY i.id;"
		);

		$Bicycles = [];

		if (!$result)
		{
			throw new \Exception($DBOperator->connect_error);
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$Bicycles[] = new Bicycle
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
			);

		}

		return $Bicycles;
	}

}