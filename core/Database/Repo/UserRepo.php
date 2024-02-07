<?php

namespace Core\Database\Repo;

use App\Model\User;


class UserRepo extends BaseRepo
{
	public static function getUserByLogin(string $login)
	{
		$connection = BaseRepo::getDbConnection();
		$login = mysqli_real_escape_string($connection, $login);
		$result = mysqli_query($connection, "
		SELECT u.id, u.name, u.address, u.password, r.name as role_name FROM user u
		         INNER JOIN role r on u.role_id = r.id
		         WHERE u.id = '{$login}';
		");
		if (!$result) {
			throw new Exception(mysqli_error($connection));
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
}