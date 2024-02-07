<?php

namespace App\Service;
use App\Config\Config;

class GetDbconnection
{

	public static function createConnection()
	{
		static $connection = null;

		if ($connection === null)
		{
			$config = new Config();

			$dbHost = $config->option('DB_HOST');
			$dbUser = $config->option('DB_USER');
			$dbPassword = $config->option('DB_PASSWORD');
			$dbName = $config->option('DB_NAME');

			$connection = mysqli_init();

			$connected = mysqli_real_connect($connection, $dbHost, $dbUser, $dbPassword, $dbName);
			if (!$connected)
			{
				$error = mysqli_connect_errno() . ': ' . mysqli_connect_error();
				throw new \Exception($error);
			}

			$encodingResult = mysqli_set_charset($connection, 'utf8');
			if (!$encodingResult)
			{
				throw new \Exception(mysqli_error($connection));
			}
		}

		return $connection;
	}
}


