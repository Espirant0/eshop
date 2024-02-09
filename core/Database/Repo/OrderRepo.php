<?php

namespace Core\Database\Repo;

use App\Model\Order;

use App\Service\DBHandler;


class OrderRepo extends BaseRepo
{
	public static function getOrderList(): array
	{
		$DBOperator = new DBHandler();
		$result = $DBOperator->query("SELECT o.id, o.item_id,o.status_id, o.data_create, o.price, o.user_id, o.address 
        FROM orders o 
		inner join status s on s.ID = o.status_id
		inner join user u on u.ID = o.user_id
		inner join item i on i.ID = o.item_id");

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
			$row['item_id'],
			$row['status_id'],
			$row['data_create'],
			$row['user_id'],
			$row['address'],
			);

		}
		return $orders;
	}

}

