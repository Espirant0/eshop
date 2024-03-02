<?php

namespace Core\Database\Repo;

use App\Model\User;
use App\Service\DBHandler;
use Core\Database\ORM\QueryBuilder;

class UserRepo extends BaseRepo
{
	public static function getUserByLogin(string $login)
	{
		$DBOperator = DBHandler::getInstance();
		$login = $DBOperator->real_escape_string($login);
		$result = $DBOperator->query(QueryBuilder::
			select('id, name, address, password, login','user')
			->join('name','role')
			->as('role.name','role_name')
			->where("user.login = $login")
		);

		if (!$result)
		{
			throw new Exception($DBOperator->connect_error);
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			$userLogin = $row['login'];

			if (!isset($userLogin))
			{
				return null;
			}
			return new User($login, $row['name'], $row['address'], $row['role_name'], $row['password']);
		}
	}

	public static function getUserList(): array
	{
		$DBOperator = DBHandler::getInstance();
		$userList = [];
		$result = $DBOperator->query(QueryBuilder::
			select('id, name, address, password','user')
			->join('name','role')
			->as('role.name','role_name')
			->orderBy('user.id')
		);
		if (!$result)
		{
			throw new Exception($DBOperator->connect_error);
		}
		while ($row = mysqli_fetch_assoc($result))
		{
			$userList[] = new User($row['id'], $row['name'], $row['address'], $row['role_name'], $row['password']);
		}
		return $userList;
	}
}