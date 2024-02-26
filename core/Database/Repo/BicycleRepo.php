<?php

namespace Core\Database\Repo;

use App\Config\Config;
use App\Model\Category;
use App\Service\DBHandler;
use App\Model\Bicycle;

class BicycleRepo extends BaseRepo
{
	public static function getBicycleList(int $currentPage, string $categoryName = '', ?array $property = []): array
	{
		$config = new Config();
		$itemsPerPage = $config->option('PRODUCT_LIMIT');
		$startId = ($currentPage - 1) * $itemsPerPage;
		$queryDop = '';

		if ($categoryName !== '')
		{
			$queryDop = "AND c2.engName = '$categoryName'";
		}

		$DBOperator = DBHandler::getInstance();
		$result = $DBOperator->query(
			"SELECT i.id, i.title, i.create_year, i.price, i.description, i.status, i.speed, c.engName as color, ma.engName as material, m.name as vendor, ta.engName as target, c2.engName as category, ic.category_id, c2.name as category_name
        FROM item i
        INNER JOIN manufacturer m on m.id = i.manufacturer_id
        INNER JOIN color c on c.id = i.color_id
        INNER JOIN material ma on ma.id = i.material_id
        INNER JOIN target_audience ta on ta.id = i.target_id
        INNER JOIN items_category ic on i.id = ic.item_id
        INNER JOIN category c2 on ic.category_id = c2.id
        WHERE i.status = 1 $queryDop AND i.id IN(SELECT id FROM item WHERE id > $startId)
        ORDER BY i.id
		LIMIT $itemsPerPage;"
		);

		$Bicycles = [];

		if (!$result)
		{
			throw new \Exception($DBOperator->connect_error);
		}
		if ($property != [])
		{
			return BicycleRepo::getFilteredBicycleList($currentPage, $categoryName, $property);
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$category[] = new Category(
				$row['category_id'],
				$row['category_name'],
				$row['category']
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
				$category,
				$row['target']
			);
			unset($category);
		}
		return $Bicycles;
	}

	public static function getFilteredBicycleList(int $currentPage, string $categoryName = '', ?array $property = []): array
	{
		$config = new Config();
		$limit = $config->option('PRODUCT_LIMIT');
		if (isset($property['search']))
		{
			$filter = $property;
			unset($filter['search']);
			if (count($filter) == 0) unset($filter);
			$property = $property['search'];
		} else
		{
			$filter = $property;
			$property = null;
		}
		$filteredBicycleList = [];
		$DBOperator = DBHandler::getInstance();
		$result = $DBOperator->query(
			"SELECT i.id, i.title, i.create_year, i.price, i.description, i.status, i.speed, c.engName as color, ma.engName as material, m.name as vendor, ta.engName as target, c2.engName as category, ic.category_id, c2.name as category_name
        FROM item i
        INNER JOIN manufacturer m on m.id = i.manufacturer_id
        INNER JOIN color c on c.id = i.color_id
        INNER JOIN material ma on ma.id = i.material_id
        INNER JOIN target_audience ta on ta.id = i.target_id
        INNER JOIN items_category ic on i.id = ic.item_id
        INNER JOIN category c2 on ic.category_id = c2.id
        WHERE i.status = 1
        ORDER BY i.id"
		);
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
			if (isset($filter))
			{
				$skip = 0;
				foreach ($filter as $value)
				{
					$key = array_search($value, $filter, true);

					if ($key === false || !isset($row[$key]))
					{
						throw new \Exception('invalid data', -1);
					}

					if ($row[$key] !== $value)
					{
						$skip = 1;
						break;
					}
				}
				if ($skip === 1) continue;
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
					$row['target']
				);
			}
			if ($pageCounter == $currentPage * $limit) break;
		}
		return $filteredBicycleList;
	}
}