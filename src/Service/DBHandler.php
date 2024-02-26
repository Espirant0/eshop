<?php

namespace App\Service;

use App\Config\Config;

class DBHandler extends \mysqli
{
	private static DBHandler $instance;
	private string $dbHost;
	private string $dbUser;
	private string $dbPassword;
	private string $dbName;

	private function __construct()
	{
		$config = new Config();
		$this->dbHost = $config->option('DB_HOST');
		$this->dbUser = $config->option('DB_USER');
		$this->dbPassword = $config->option('DB_PASSWORD');
		$this->dbName = $config->option('DB_NAME');

		parent::__construct($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbName);

		if ($this->connect_error)
		{
			throw new \Exception($this->connect_error);
		}

		$this->set_charset('utf8');
		unset($config);
	}

	public static function getInstance(): DBHandler
	{
		if (!isset(self::$instance))
		{
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function reconnect(): void
	{
		$this->close();

		if (!$this->real_connect($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbName))
		{
			throw new \Exception($this->connect_error);
		}
	}

	/**
	 * Функция принимает SQL-команду и возвращает ассоциативный массив вида [[№строки]=>[[имя столбца]=>[значение]]]
	 * @param string $sqlQuery Запрос к БД
	 * @return array Массив ответа от БД
	 */
	public function getResult(string $sqlQuery): array
	{
		return $this->query($sqlQuery)->fetch_all(MYSQLI_ASSOC);
	}

	/**
	 * Функция принимает SQL-команду и возвращает ассоциативный массив вида [[№строки]=>[[имя столбца]=>[значение]]]
	 * Лучше, наверное, писать через try catch
	 * @param string $sqlQuery Запрос к БД
	 * @return array Массив ответа от БД
	 * @throws \Exception в случае неудачи
	 */
	public static function getResultStatic(string $sqlQuery): array|\Exception
	{
		$config = new Config();

		$connection = mysqli_init();
		$connected = mysqli_real_connect(
			$connection,
			$config->option('DB_HOST'),
			$config->option('DB_USER'),
			$config->option('DB_PASSWORD'),
			$config->option('DB_NAME')
		);

		if (!$connected)
		{
			unset($config);
			return new \Exception($connection->connect_error);
		}

		$connection->set_charset('utf8');
		unset($config);

		return $connection->query($connection->real_escape_string($sqlQuery))->fetch_all(MYSQLI_ASSOC);
	}


}