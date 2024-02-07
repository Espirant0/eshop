<?php

namespace Core\Database\Migration;

use App\Config\Config;
use Core\Database\Repo\BaseRepo;

class Migrator
{
	public static function migrate(): void
	{
		$connection = BaseRepo::getDbConnection();
		$mSQL="SHOW TABLES LIKE 'migration'";
		$res = mysqli_query($connection, $mSQL);
		if ($res->num_rows === 0)
		{
			Migrator::deleteData();
			$mSQL = file_get_contents(ROOT . '/src/Migration/2024.03.02_20.50_migration_initiation.sql');
			mysqli_query($connection, $mSQL);
			mysqli_query($connection, "INSERT INTO migration (name) VALUES ('2024.03.02_20.50_migration_initiation.sql')");
		}
		$doneMigrationsQuery = mysqli_query($connection, 'SELECT * FROM migration');
		if (!$doneMigrationsQuery)
		{
			throw new Exception(mysqli_error($connection));
		}
		$doneMigrations = [];
		while ($row = mysqli_fetch_assoc($doneMigrationsQuery))
		{
			$doneMigrations[] = $row['name'];
		}
		$migrations=Migrator::getMigrationFiles();
		foreach ($migrations as $migration)
		{
			if(!in_array($migration, $doneMigrations))
			{
				$commands = file_get_contents(ROOT . '/src/Migration/' . $migration);
				$commandList=explode(';',$commands);
				foreach ($commandList as $commandSQL)
				{
					if ($commandSQL=='') continue;
					mysqli_query($connection, $commandSQL);
				}
				mysqli_query($connection, "INSERT INTO migration (name) VALUES ('$migration')");
			}
		}
	}


	public static function getMigrationFiles():array
	{
		$migrationFiles=[];
		$migrations=scandir(ROOT . '/src/Migration');
		foreach ($migrations as $migration)
		{
			if(preg_match('/.(sql)/', $migration))
			{
				$migrationFiles[]=$migration;
			}
		}
		return $migrationFiles;
	}
	public static function deleteData():void
	{
		$config = new Config();
		$DB_NAME = $config->option('DB_NAME');
		$connection = BaseRepo::getDbConnection();
		$res = mysqli_query($connection, 'SHOW TABLES');
		mysqli_query($connection, 'SET foreign_key_checks = 0');
		$tables = 'Tables_in_' . $DB_NAME;
		while($row=mysqli_fetch_assoc($res))
		{
			$tableName = $row[$tables];
			$mSQL="DROP TABLE $tableName";
			mysqli_query($connection, $mSQL);
		}
		mysqli_query($connection, 'SET foreign_key_checks = 1');
	}
}