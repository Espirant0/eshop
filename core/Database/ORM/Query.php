<?php

namespace Core\Database\ORM;

use App\Service\DBHandler;
use App\Service\ExceptionHandler;
use App\Service\Logger;

class Query
{
	private array $queryTables = [];
	private string $query = '';
	private array $usedFunctions = [];
	private array $usedColumns = [];
	private array $usedRenaming = [];

	public function __construct(string $query, string $table)
	{
		$this->query = $query;
		$this->queryTables[] = $table;
	}

	public function addRenameToList(string $column, string $name): void
	{
		$this->usedRenaming[] = ["$column" => "$name"];
	}

	/**
	 * @return string
	 */
	public function getQuery(): string
	{
		return $this->query;
	}

	public function setQuery($query): void
	{
		$this->query = $query;
	}

	public function addToQuery($query): void
	{
		$this->query = $this->query . ' ' . $query;
	}

	/**
	 * @return array
	 */
	public function getQueryTables(): array
	{
		return $this->queryTables;
	}

	/**
	 * @param array $queryTables
	 */
	public function setQueryTables(array $queryTables): void
	{
		$this->queryTables = $queryTables;
	}

	/**
	 * @param array $queryTables
	 */

	public function addQueryTable(string $table): void
	{
		$this->queryTables[] = $table;
	}

	/**
	 * @return array
	 */
	public function getUsedFunctions(): array
	{
		return $this->usedFunctions;
	}


	public function addUsedFunction(string $usedFunction): void
	{
		$this->usedFunctions[] = $usedFunction;
	}

	public function testQuery(string $initiatorFunction): bool
	{
		restore_error_handler();
		restore_exception_handler();
		try
		{
			DBHandler::getInstance()->query($this->getQuery());
		}
		catch (\Error|\Exception $e)
		{
			set_error_handler([ExceptionHandler::getInstance(), 'errorToLogger']);
			set_exception_handler([ExceptionHandler::getInstance(), 'exceptionToLogger']);
			Logger::ORMLogging("Unable to proceed query [{$this->getQuery()}]!","[{$initiatorFunction}->testQuery]");
			throw new \Exception('ORM-exception',-2);
		}
		finally
		{
			return true;
		}
	}

	public function addUsedColumns(array|string $columns): void
	{
		if (!is_array($columns))
		{
			$columns = str_replace(' ', '', $columns);
			$columns = explode(',', $columns);
		}
		foreach ($columns as $column) $this->usedColumns[] = $column;
	}

	public function getUsedColumns(): array
	{
		return $this->usedColumns;
	}

	public function __toString(): string
	{
		return $this->query;
	}
}