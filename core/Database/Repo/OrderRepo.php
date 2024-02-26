<?php

namespace Core\Database\Repo;

use App\Cache\FileCache;
use App\Model\Bicycle;
use App\Model\CategoryList;
use App\Model\Order;
use App\Service\DBHandler;

class OrderRepo extends BaseRepo
{
	public static function getOrderList(): array
	{
		$DBOperator = DBHandler::getInstance();
		$result = $DBOperator->query(
			"SELECT o.id, i.title as item, s.name as status, o.data_create, o.user_id, o.price, o.address
					FROM orders o
						 INNER JOIN status s on s.id = o.status_id
						 INNER JOIN item i on i.id = o.item_id
	        ");

		$orders = [];

		if (!$result)
		{
			throw new \Exception($DBOperator->connect_error);
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$orders[] = new Order
			(
				$row['id'],
				$row['item'],
				$row['status'],
				$row['address'],
				$row['user_id'],
				$row['price'],
				$row['data_create'],
			);
		}
		return $orders;
	}

	public static function saveOrder($itemId, int $price, string $number, string $address): void
	{
		$DBOperator = DBHandler::getInstance();
		$date = date('Y-m-d');
		$number = $DBOperator->real_escape_string(htmlspecialchars($number));
		$address = $DBOperator->real_escape_string(htmlspecialchars($address));
		$DBOperator->query("SET FOREIGN_KEY_CHECKS = 0;");
		$result = $DBOperator->query(
			"INSERT INTO orders (item_id, status_id, data_create, price, user_id, address) 
					VALUES ($itemId, 1, '$date', '$price', '$number', '$address')
		;");
		if (!$result)
		{
			throw new \Exception($DBOperator->connect_error);
		}
	}
}