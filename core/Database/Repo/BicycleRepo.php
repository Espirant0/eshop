<?php

namespace Core\Database\Repo;

use App\Model\Category;
use App\Service\DBHandler;
use App\Model\Bicycle;
class BicycleRepo extends BaseRepo
{
	public static function getBicycleList($categoryName): array
	{
        $queryDop = '';
        if ($categoryName !== '') {
            $queryDop = "AND c2.engName = '$categoryName'";
        }

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
        WHERE i.status = 1 $queryDop
        ORDER BY i.id;"
        );

		$Bicycles = [];

		if (!$result)
		{
			throw new \Exception($DBOperator->connect_error);
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$category[] = new Category(
				$row['category_id'],
				$row['category_name'],
				$row['category_engname']
			);
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
				$row['speed'],
				$category
			);
			unset($category);

		}

		return $Bicycles;
	}

}