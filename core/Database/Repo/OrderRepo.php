<?php

namespace Core\Database\Repo;

use App\Model\Order;
use App\Service\DBHandler;
use Core\Database\ORM\QueryBuilder;

class OrderRepo
{
	public static function saveOrder($itemId, int $price, string $number, string $address): void
	{
		$DBOperator = DBHandler::getInstance();
		$date = date('Y-m-d');
		$number = $DBOperator->real_escape_string(htmlspecialchars($number));
		$address = $DBOperator->real_escape_string(htmlspecialchars($address));
		QueryBuilder::insert('orders', 'item_id, status_id, data_create, price, user_id, address',
			"$itemId, 1, $date, $price, $number, $address");
	}
}