<?php

namespace Core\Database\Repo;

use App\Cache\FileCache;
use App\Model\Bicycle;
use App\Model\CategoryList;
use App\Model\Order;
use App\Service\DBHandler;
use Core\Database\ORM\QueryBuilder;

class OrderRepo extends BaseRepo
{
	public static function getOrderList(): array
	{
		$DBOperator = DBHandler::getInstance();
		$result = $DBOperator->query(QueryBuilder::
			select('id, data_create, user_id, price, address','orders')
			->join('name','status')
			->join('title','item')
			->as(['item.title','status.name'],['item','status'])
			);

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
		QueryBuilder::insert('orders','item_id, status_id, data_create, price, user_id, address',
			"$itemId, 1, $date, $price, $number, $address");
	}
}