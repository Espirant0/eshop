<?php

namespace Core\Database\Repo;

use App\Model\User;
use App\Service\DBHandler;


class UserRepo extends BaseRepo
{
	public static function getUserByLogin(string $login)
	{
		$DBOperator = new DBHandler();
		$login = $DBOperator->real_escape_string($login);
		$result = $DBOperator->query("
		SELECT u.id, u.name, u.address, u.password, r.name as role_name FROM user u
		         INNER JOIN role r on u.role_id = r.id
		         WHERE u.id = '{$login}';
		");
		if (!$result) {
			throw new Exception($DBOperator->connect_error);
		}
		while ($row = mysqli_fetch_assoc($result))
		{
			$userLogin = $row['id'];
			$userName = $row['name'];
			$userAddress = $row['address'];
			$userRole = $row['role_name'];
			$userPassword = $row['password'];
		}
		if(!isset($userLogin))
		{
			return null;
		}
		return new User($login, $userName, $userAddress, $userRole, $userPassword);
	}

	public static function getUserList():array
	{
		$DBOperator = new DBHandler();
		$userList = [];
		$result = $DBOperator->query("
		SELECT u.id, u.name, u.address, u.password, r.name as role_name FROM user u
		         INNER JOIN role r on u.role_id = r.id
		ORDER BY u.id
		");
		if (!$result) {
			throw new Exception($DBOperator->connect_error);
		}
		while ($row = mysqli_fetch_assoc($result))
		{
			$userList[] = new User($row['id'], $row['name'], $row['address'],$row['role_name'], $row['password']);
		}
		return $userList;
	}
}