<?php

namespace Core\Database\Migration;

use App\Config\Config;
use App\Service\DBHandler;

class Migrator
{
	public static function migrate(): void
	{
		$DBOperator = DBHandler::getInstance();

		if ($DBOperator->query("SHOW TABLES LIKE 'migration'")->num_rows === 0)
		{
			self::deleteData();
			$mSQL = file_get_contents(ROOT . '/src/Migration/2024.03.02_20.50_migration_initiation.sql');

			$DBOperator->query($mSQL);
			$DBOperator->query("INSERT INTO migration (name) VALUES ('2024.03.02_20.50_migration_initiation.sql')");
		}

		$doneMigrationsQuery = $DBOperator->getResult('SELECT * FROM migration');
		$doneMigrations = [];

		foreach ($doneMigrationsQuery as $migration)
		{
			$doneMigrations[] = $migration['name'];
		}

		$migrations = self::getMigrationFiles();

		foreach ($migrations as $migration)
		{
			if (!in_array($migration, $doneMigrations))
			{
				$commands = file_get_contents(ROOT . '/src/Migration/' . $migration);
				$commandList = explode(';', $commands);

				foreach ($commandList as $commandSQL)
				{
					$testCommand = trim($commandSQL);
					if ($testCommand == '')
					{
						continue;
					}
					$DBOperator->query($commandSQL);
				}

				$DBOperator->query("INSERT INTO migration (name) VALUES ('$migration')");
			}
		}
		unset($DBOperator);
	}

	public static function getMigrationFiles(): array
	{
		$migrationFiles = [];
		$migrations = scandir(ROOT . '/src/Migration');

		foreach ($migrations as $migration)
		{
			if (preg_match('/.(sql)/', $migration))
			{
				$migrationFiles[] = $migration;
			}
		}

		return $migrationFiles;
	}

	public static function deleteData(): void
	{
		$DBOperator = DBHandler::getInstance();
		$config = new Config();
		$res = $DBOperator->query('SHOW TABLES');
		$DBOperator->query('SET foreign_key_checks = 0');
		$tables = 'Tables_in_' . strtolower($config->option('DB_NAME'));

		while ($row = mysqli_fetch_assoc($res))
		{
			$tableName = $row[$tables];
			$mysql = "DROP TABLE {$tableName}";
			$DBOperator->query($mysql);
		}

		$DBOperator->query('SET foreign_key_checks = 1');
	}
}