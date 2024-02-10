<?php

namespace Core\Database\Repo;
use App\Model\Bicycle;
use App\Service\DBHandler;

class DetailRepo extends BaseRepo
{
	public static function getBicycleListById(int $id): array
	{
		$DBOperator = new DBHandler();
		$result = $DBOperator->query(
			"SELECT i.id, i.title, i.price, i.description, ta.name as target
		            FROM item i
		                INNER JOIN target_audience ta on ta.id = i.target_id
		            WHERE i.id = '{$id}';"
        );

		$item = [];

		if (!$result)
		{
			throw new \Exception($DBOperator->connect_error);
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$item[] = new Bicycle
			(
				$row['id'],
				$row['title'],
				$row['price'],
				$row['target'],
			);
		}
		return $item;
	}

}