<?php

namespace Core\Database\Repo;

use App\Model\User;
use App\Service\DBHandler;
use Core\Database\ORM\QueryBuilder;
use Exception;

class UserRepo
{
	public static function getUserByLogin(string $login): User|null
	{
		$DBOperator = DBHandler::getInstance();
		$login = $DBOperator->real_escape_string($login);
		$result = $DBOperator->query(QueryBuilder::
		select('id, name, address, password, login', 'user')
			->join('name', 'role')
			->as('role.name', 'role_name')
			->where("user.login = $login")
		);

		if (!$result)
		{
			throw new Exception($DBOperator->connect_error);
		}

		$row = mysqli_fetch_assoc($result);
		$userLogin = $row['login'];
		if (!isset($userLogin))
		{
			return null;
		}
		return new User($login, $row['name'], $row['address'], $row['role_name'], $row['password']);
	}
}