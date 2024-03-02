<?php

namespace Core\Database\ORM;

use App\Config\Config;
use App\Service\DBHandler;
use App\Service\Logger;

class QueryBuilder
{
	public const ASCENDING = 1;
	public const DESCENDING = 2;
	public const INNER = 1;
	public const LEFT = 2;
	public const RIGHT = 3;
	public const FULL = 4;
	public const CROSS = 5;
	public const AVERAGE = 1;
	public const COUNT = 2;
	public const SUM = 3;
	public const MIN =4;
	public const MAX = 5;
	private Query $query;
	private array $dbScheme = [];

	public function __construct(Query $query = new Query('', ''))
	{
		$this->query = $query;
		$config = Config::getInstance();
		$dbName = $config->option("DB_NAME");
		$dbTables = DBHandler::getInstance()->getResult("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$dbName' AND TABLE_TYPE = 'BASE TABLE'");
		foreach ($dbTables as $dbTable)
		{
			$this->dbScheme[$dbTable['TABLE_NAME']] = [];
			$columns = DBHandler::getInstance()->getResult("SHOW COLUMNS FROM {$dbTable['TABLE_NAME']}");
			foreach ($columns as $column)
			{
				$result = DBHandler::getInstance()->getResult("SELECT DATA_TYPE, COLUMN_DEFAULT, CHARACTER_MAXIMUM_LENGTH, COLUMN_KEY, EXTRA  FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '$dbName' AND TABLE_NAME = '{$dbTable['TABLE_NAME']}' AND COLUMN_NAME = '{$column['Field']}'");
				$this->dbScheme[$dbTable['TABLE_NAME']][$column['Field']] = $result[0];
			}
		}
	}

	/**
	 * @param Query $query
	 */
	public function setQuery(Query $query): void
	{
		$this->query = $query;
	}

	/**
	 * @return array
	 */
	public function getDbScheme(): array
	{
		return $this->dbScheme;
	}

	private function getQueryObject(): Query
	{
		return $this->query;
	}

	public function getQuery(): string
	{
		return $this->query->getQuery();
	}

	public function getTableRestrictions(string $table): array
	{
		$restrictions = [];
		$columns = $this->dbScheme[$table];
		$allColumnsCount = count($columns);
		$keys = array_keys($columns);
		for ($i = 0; $i < $allColumnsCount; $i ++)
		{
			$restrictions[$keys[$i]] = $columns[$keys[$i]];
		}
		return $restrictions;
	}

	private function isTableExists(string $table): bool
	{
		if(in_array($table, array_keys($this->dbScheme)))
		{
			return true;
		}
		return false;
	}

	private function getTableColumnsNames(string $table): array
	{
		return array_keys($this->dbScheme[$table]);
	}

	private function isColumnExistInTable(string $columnName, string $table): bool
	{
		if (in_array($columnName, array_keys($this->dbScheme[$table])))
		{
			return true;
		}
		return false;
	}
	#private function isDataTypeCorrectForDb()
	private function itemListHandler(string|array $items, string $table, string $initiatorFunctionForLog = ''): string
	{
		if (!is_array($items))
		{
			$items = str_replace(' ','', $items);
			$items = explode(',', $items);
		}
		foreach ($items as $item)
		{
			if (!$this->isColumnExistInTable($item, $table))
			{
				Logger::ORMLogging("Column \"$item\" is not exists in table \"$table\"","[{$initiatorFunctionForLog}->itemListHandler]");
				throw new \Exception('ORM-exception',-2);
			}
		}
		return $table . '.' . implode(", $table.", $items);
	}

	public static function select(string|array $itemList, string $table, bool $blacklist = false, bool $distinct = false): self
	{
		$queryInitiator = new QueryBuilder();
		if (strtolower($itemList) === 'all')
		{
			$itemList = implode(', ', $queryInitiator->getTableColumnsNames($table));
			$blacklist = false;
		}
		if (strtolower($itemList) === '*')
		{
			$items = '*';
			$blacklist = false;
		}
		else
		{
			$items = $queryInitiator->itemListHandler($itemList, $table, 'SELECT');
		}
		if ($blacklist)
		{
			$columns = $queryInitiator->getTableColumnsNames($table);
			$items = $queryInitiator->itemListHandler(implode(', ', array_diff($columns, explode(', ', $itemList))), $table);
		}
		if ($distinct)
		{
			$query = new Query("SELECT DISTINCT $items FROM $table", $table);
			$query->addUsedFunction('SELECT DISTINCT');
		}
		else
		{
			$query = new Query("SELECT $items FROM $table", $table);
			$query->addUsedFunction('SELECT');
		}
		$query->addUsedColumns($items);
		$queryInitiator->setQuery($query);
		return $queryInitiator;
	}

	public function join(string|array $itemList, string $table, string $by = 'id', int $flag = self::INNER): self
	{
		if (!$this->isTableExists($table))
		{
			Logger::ORMLogging("$table is not exists","[JOIN]");
			throw new \Exception('ORM-exception',-2);
		}
		if ($itemList != '')
		{
			$items = ', ' . self::itemListHandler($itemList, $table, 'JOIN');
		}
		$joinType = match ($flag)
		{
			self::LEFT => 'LEFT JOIN',
			self::RIGHT => 'RIGHT JOIN',
			self::FULL => 'FULL JOIN',
			self::CROSS => 'CROSS JOIN',
			default => 'INNER JOIN',
		};
		$usedTables = $this->query->getQueryTables();
		if ($by === 'id')
		{
			$check = false;
			foreach ($usedTables as $usedTable)
			{
				$tableColumns = self::getTableColumnsNames($usedTable);
				if (in_array("$table" . "_id", $tableColumns))
				{
					$this->query->addToQuery("$joinType $table ON $table.id = $usedTable.$table" . "_id");
					$check = true;
					break;
				}
				if (in_array("$usedTable" . "_id", self::getTableColumnsNames($table)))
				{
					$this->query->addToQuery("$joinType $table ON $table.$usedTable" . "_id = $usedTable" . ".id");
					$check = true;
					break;
				}
			}
			if ($check)
			{
				$this->query->addQueryTable($table);
				$this->query->addUsedFunction($joinType);
			}
			else
			{
				Logger::ORMLogging("Can't find direct \"id\"-connection between $table and used tables(" . implode(',', $usedTables) . ")","[JOIN]");
				throw new \Exception('ORM-exception',-2);
			}
		}
		else
		{
			$query = "$joinType $table ON ";
			$by = explode('=', str_replace(' ', '', $by));
			$tableColumns = self::getTableColumnsNames($table);
			$check = false;
			if (in_array(explode('.', $by[0])[1], $tableColumns))
			{
				$query = $query . "$by[0] = ";
				$check = true;
			}
			foreach ($usedTables as $usedTable)
			{
				$tableColumns = self::getTableColumnsNames($usedTable);
				if (in_array(explode('.', $by[1])[1], $tableColumns))
				{
					$query = $query . $by[1];
					$check = true;
					break;
				}
			}
			if (!$check)
			{
				Logger::ORMLogging("Wrong condition! $by[1] is not exists in $table's columns","[JOIN]");
				throw new \Exception('ORM-exception',-2);
			}
			$this->query->addToQuery($query);
			$this->query->addQueryTable($table);
			$this->query->addUsedFunction($joinType);
		}
		$query = $this->query->getQuery();
		$query = explode(' FROM ', $query);
		if (isset($items))
		{
			$query[0] = $query[0] . "$items FROM ";
		}
		else
		{
			$query[0] = $query[0] . " FROM ";
		}
		if (is_array($itemList))
		{
			foreach ($itemList as $item)
			{
				if ($item != '')
				{
					$this->query->addUsedColumns("$table.$item");
				}
			}
		}
		else
		{
			if ($itemList != '')
			{
				$this->query->addUsedColumns("$table.$itemList");
			}
		}
		$this->query->setQuery(implode('', $query));
		return $this;
	}

	public function where(string $condition, ?QueryBuilder $selectQuery = null, string $typeOfAddition = 'AND'): self
	{
		$conditionCheck = str_replace(' ','',$condition);
		$conditionCheck = str_replace(['<=>','<=','>=','<>','=','<','>'],' ',$conditionCheck);
		$conditionCheck = explode(' ', $conditionCheck);
		if ($selectQuery instanceof QueryBuilder)
		{
			if(in_array('WHERE',$this->query->getUsedFunctions()))
			{
				$this->query->addToQuery("$typeOfAddition $condition IN($selectQuery)");
			}
			else
			{
				$this->query->addToQuery("WHERE $condition IN($selectQuery)");
			}
			$selectQuery->query->testQuery('WHERE');
			foreach ($selectQuery->getQueryObject()->getUsedFunctions() as $function)
			{
				$this->query->addUsedFunction($function);
			}
			$this->query->addUsedFunction('WHERE');
			return $this;
		}
		if (str_contains($conditionCheck[0],'.')
			|| str_contains($conditionCheck[1],'.'))
		{
			$stringCondition = explode('.',$conditionCheck[0])[1];
			$check = false;
			foreach ($this->query->getQueryTables() as $table)
			{
				if (in_array($stringCondition, $this->getTableColumnsNames($table)))
				{
					$check = true;
					break;
				}
			}
			if (!$check)
			{
				Logger::ORMLogging("Unknown condition column. This ($conditionCheck[0]) is not used in Query Tables.",'[WHERE]');
				throw new \Exception('ORM-exception',-2);
			}
			if (count(explode('.', $conditionCheck[1]))>1)
			{
				$check = false;
			}
			if (!is_numeric($conditionCheck[1]) && !$check)
			{
				$stringCondition = explode('.',$conditionCheck[1])[1];
				foreach ($this->query->getQueryTables() as $table)
				{
					if (in_array($stringCondition, $this->getTableColumnsNames($table)))
					{
						$check = true;
						break;
					}
				}
				if (!$check)
				{
					Logger::ORMLogging("Unknown condition column. This ($conditionCheck[1]) is not used in Query Tables.",'[WHERE]');
					throw new \Exception('ORM-exception',-2);
				}
			}
		}
		if (!in_array('WHERE', $this->query->getUsedFunctions()))
		{
			$this->query->addToQuery('WHERE ' . $condition);
		}
		else
		{
			if (strtolower($typeOfAddition) != ('and' || 'or' || 'not'))
			{
				Logger::ORMLogging("Unknown condition statement ($typeOfAddition)",'[WHERE]');
				throw new \Exception('ORM-exception',-2);
			}
			$this->query->addToQuery("$typeOfAddition " . $condition);
		}
		$this->query->addUsedFunction('WHERE');
		$this->query->testQuery('WHERE');
		return $this;
	}

	public function orderBy(string $conditionColumn, int $flag = self::ASCENDING, ?int $limit = null): self
	{
		$order = match ($flag)
		{
			self::DESCENDING => 'DESC',
			default => 'ASC',
		};
		if (in_array($conditionColumn, $this->query->getUsedColumns()))
		{
			if (isset($limit))
			{
				$this->query->addToQuery(" ORDER BY $conditionColumn $order LIMIT $limit");
			}
			else
			{
				$this->query->addToQuery(" ORDER BY $conditionColumn $order");
			}
			$this->query->addUsedFunction('ORDER');
		}
		else
		{
			Logger::ORMLogging("Column $conditionColumn that is used to order query is not exists in query's tables.", '[ORDER_BY]');
			throw new \Exception('ORM-exception',-2);
		}
		return $this;
	}

	public function as(string|array $nameToApply, string|array $asName): self
	{
		if (is_array($nameToApply) && is_array($asName))
		{
			if(count($nameToApply) != count($asName))
			{
				Logger::ORMLogging("Different count of arrays.", '[AS]');
				throw new \Exception('ORM-exception',-2);
			}
			$maxPos = count($nameToApply);
			for ($i = 0; $i < $maxPos; $i++)
			{
				$query = explode('FROM', $this->getQuery());
				$query[0] = str_replace("$nameToApply[$i]", "$nameToApply[$i] AS $asName[$i]", $query[0]);
				$this->query->setQuery(implode('FROM', $query));
				$this->query->addRenameToList($nameToApply[$i], $asName[$i]);
			}
		}
		elseif (is_string($nameToApply) && is_string($asName))
		{
			$query = explode('FROM', $this->getQuery());
			$query[0] = str_replace("$nameToApply", "$nameToApply AS $asName", $query[0]);
			$this->query->setQuery(implode('FROM', $query));
			$this->query->addRenameToList($nameToApply, $asName);
		}
		return $this;
	}

	public static function insert(string $table, array|string $column, array|string $value): void
	{
		$queryInitiator = new QueryBuilder();
		$columnRestrictions = $queryInitiator->getTableRestrictions($table);
		if ($column === '*')
		{
			$column = '';
			$keys = array_keys($columnRestrictions);
			$maxColumnCount = count($keys);
			for ($i = 0; $i < $maxColumnCount; $i++)
			{
				if ($columnRestrictions[$keys[$i]]["EXTRA"] != 'auto_increment')
				{
					$column = $column . $keys[$i] . ', ';
				}
			}
			$column = $column . ',';
			$column = str_replace(', ,', '', $column);
		}
		if (is_string($column))
		{
			$column = str_replace(', ', ',', $column);
			$column = explode(',', $column);
		}
		if (is_string($value))
		{
			$value = str_replace(', ', ',', $value);
			$value = explode(',', $value);
		}
		$config = Config::getInstance();
		$restrictions = $config->option("DB_CHARACTERS");
		if (!$queryInitiator->isTableExists($table))
		{
			Logger::ORMLogging("Table with name $table is not exists", '[INSERT]');
			throw new \Exception('ORM-exception',-2);
		}
		$requiredColumns = [];
		foreach ($columnRestrictions as $restriction)
		{
			if ($restriction['COLUMN_KEY'] === 'MUL'
				&& $restriction['COLUMN_DEFAULT'] === null
				&& $restriction['EXTRA'] != 'auto_increment')
			{
				$requiredColumns[] = array_search($restriction, $columnRestrictions);
			}
		}
		if (array_diff($requiredColumns, $column) != [])
		{
			Logger::ORMLogging("INCORRECT amount of values. Want at least " . count($requiredColumns) . ' but get ' . count($column), '[INSERT]');
			throw new \Exception('ORM-exception',-2);
		}
		$columnDefaultCount = count($columnRestrictions) - count($requiredColumns);
		if (count($value) < count($column) - $columnDefaultCount)
		{
			Logger::ORMLogging("INCORRECT amount of values. Want at least " . count($column) - $columnDefaultCount . ' but get ' . count($value), '[INSERT]');
			throw new \Exception('ORM-exception',-2);
		}
		$columns = implode(', ', $column);
		$query = "INSERT INTO $table" . "($columns) VALUES(";
		$valueKey = 0;
		foreach ($column as $col)
		{
			if (!$queryInitiator->isColumnExistInTable($col, $table))
			{
				Logger::ORMLogging("Column with name $col is not exists in table $table", '[INSERT]');
				throw new \Exception('ORM-exception',-2);
			}
			$value[$valueKey] = mysqli_real_escape_string(DBHandler::getInstance(), $value[$valueKey]);
			$maxChar = (int)$columnRestrictions[$col]['CHARACTER_MAXIMUM_LENGTH'];
			if (($columnRestrictions[$col]["EXTRA"] === 'auto_increment'))
			{
				Logger::ORMLogging("Trying to insert data in \"auto_increment\" column", '[INSERT]');
				throw new \Exception('ORM-exception',-2);
			}
			if ($maxChar != 0
				&& $maxChar < mb_strlen($value[$valueKey]))
			{
				Logger::ORMLogging("Trying to write more chars then can! (max:$maxChar, insert:" . mb_strlen($value[$valueKey]) . " at $col)", '[INSERT]');
				throw new \Exception('ORM-exception',-2);
			}
			if (in_array($columnRestrictions[$col]['DATA_TYPE'], array_keys($restrictions)))
			{
				$dataType = $restrictions[$columnRestrictions[$col]['DATA_TYPE']];
			}
			else
			{
				Logger::ORMLogging("Incorrect DATA_TYPE matching! Check if the Config-file was correctly configured at DB_CHARACTERS", '[INSERT]');
				throw new \Exception('ORM-exception',-2);
			}
			if ($dataType === 'int')
			{
				if (!is_numeric($value[$valueKey]))
				{
					Logger::ORMLogging("Incorrect DATA_TYPE matching! Input value ($value[$valueKey]) is not of type 'int'", '[INSERT]');
					throw new \Exception('ORM-exception',-2);
				}
				$value[$valueKey] = (int)$value[$valueKey];
				$query = $query . "$value[$valueKey], ";
			}
			elseif ($dataType === 'date')
			{
				$date = \DateTime::createFromFormat('Y-m-d', $value[$valueKey]);
				if ($date !== false && !array_sum($date::getLastErrors()))
				{
					$query = $query . "'$value[$valueKey]', ";
				}
				else
				{
					Logger::ORMLogging("Incorrect DATA_TYPE matching! Input value ($value[$valueKey]) is not of type 'date'", '[INSERT]');
					throw new \Exception('ORM-exception',-2);
				}
			}
			else
			{
				$value[$valueKey] = (string)$value[$valueKey];
				$query = $query . "'$value[$valueKey]', ";
			}
			$valueKey++;
		}
		$query = $query . ")";
		$query = str_replace(', )', ')', $query);
		DBHandler::getInstance()->query("SET FOREIGN_KEY_CHECKS = 0;");
		(new Query($query,''))->testQuery('INSERT');
		DBHandler::getInstance()->query("SET FOREIGN_KEY_CHECKS = 1;");
		Logger::ORMLogging("All inserts done correctly!", '[INSERT]');
	}

	public static function update(string $table, array|string $column, array|string $newValue, array|string|int $updateConditions):string
	{
		$config = Config::getInstance();
		$queryInitiator = new QueryBuilder();
		$restrictions = $config->option("DB_CHARACTERS");
		if (is_string($column))
		{
			$column = str_replace(', ', ',', $column);
			$column = explode(',', $column);
		}
		if (is_string($newValue))
		{
			$newValue = str_replace(', ', ',', $newValue);
			$newValue = explode(',', $newValue);
		}
		if (is_string($updateConditions))
		{
			$updateConditions = str_replace(', ', ',', $updateConditions);
			$updateConditions = explode(',', $updateConditions);
		}
		if (is_int($updateConditions))
		{
			$updateConditions = ["id = $updateConditions"];
		}
		$columnRestrictions = $queryInitiator->getTableRestrictions($table);
		if (!$queryInitiator->isTableExists($table))
		{
			Logger::ORMLogging("Table $table is not exists", '[UPDATE]');
			throw new \Exception('ORM-exception',-2);
		}
		$valueKey = 0;
		echo "\n";
		if (count($column) != count($newValue))
		{
			Logger::ORMLogging("INCORRECT amount between columns and values. (" . count($column) . ' != ' . count($newValue) . ')', '[UPDATE]');
			throw new \Exception('ORM-exception',-2);
		}
		if (count($column) != count($updateConditions) && count($updateConditions) != 1)
		{
			Logger::ORMLogging("INCORRECT amount between columns and conditions. (" . count($column) . ' != ' . count($updateConditions) . ')', '[UPDATE]');
			throw new \Exception('ORM-exception',-2);
		}
		if (count($updateConditions) > 1)
		{
			$conditionsKey = 0;
		}
		$queryList = [];
		foreach ($column as $col)
		{
			$query = "UPDATE $table SET ";
			$newValue[$valueKey] = mysqli_real_escape_string(DBHandler::getInstance(), $newValue[$valueKey]);
			$maxChar = (int)$columnRestrictions[$col]['CHARACTER_MAXIMUM_LENGTH'];
			if (!$queryInitiator->isColumnExistInTable($col, $table))
			{
				Logger::ORMLogging("Column $col is not exists in table $table", '[UPDATE]');
				throw new \Exception('ORM-exception',-2);
			}
			if (($columnRestrictions[$col]["EXTRA"] === 'auto_increment'))
			{
				Logger::ORMLogging("Trying to insert data in \"auto_increment\" column", '[UPDATE]');
				throw new \Exception('ORM-exception',-2);
			}
			$query = $query . "$col = ";
			if ($maxChar != 0
				&& $maxChar < mb_strlen($newValue[$valueKey]))
			{
				Logger::ORMLogging("Trying to write more chars then can! (max:$maxChar, insert:" . mb_strlen($newValue[$valueKey]) . " at $col)", '[INSERT]');
				throw new \Exception('ORM-exception',-2);
			}
			if (in_array($columnRestrictions[$col]['DATA_TYPE'], array_keys($restrictions)))
			{
				$dataType = $restrictions[$columnRestrictions[$col]['DATA_TYPE']];
			}
			else
			{
				Logger::ORMLogging("Incorrect DATA_TYPE matching! Check if the Config-file was correctly configured at DB_CHARACTERS", '[INSERT]');
				throw new \Exception('ORM-exception',-2);
			}
			if ($dataType === 'int')
			{
				if (!is_numeric($newValue[$valueKey]))
				{
					Logger::ORMLogging("Incorrect DATA_TYPE matching! Input value ($newValue[$valueKey]) is not of type 'int'", '[INSERT]');
					throw new \Exception('ORM-exception',-2);
				}
				$newValue[$valueKey] = (int)$newValue[$valueKey];
				if (isset($conditionsKey))
				{
					$query = $query . "$newValue[$valueKey] WHERE $updateConditions[$conditionsKey]";
					$conditionsKey++;
				}
				else
				{
					$query = $query . "$newValue[$valueKey] WHERE $updateConditions[0]";
				}
			}
			else
			{
				$newValue[$valueKey] = (string)$newValue[$valueKey];
				if (isset($conditionsKey))
				{
					$query = $query . "'$newValue[$valueKey]' WHERE $updateConditions[$conditionsKey]";
					$conditionsKey++;
				}
				else
				{
					$query = $query . "'$newValue[$valueKey]' WHERE $updateConditions[0]";
				}
			}
			$valueKey++;
			$queryList[] = $query;
		}
		foreach ($queryList as $query)
		{
			(new Query($query, $table))->testQuery('UPDATE');
		}
		Logger::ORMLogging("All updates done correctly!", '[UPDATE]');
		return $query;
	}

	public function aggregate(string $column, int $function = self::COUNT, ?string $as = null, ?string $groupBy = null): self
	{
		$aggregateTables = $this->query->getQueryTables();
		$exist = false;
		if(count($aggregateTables)>1)
		{
			foreach ($aggregateTables as $table)
			{
				if (self::isColumnExistInTable(explode('.', $column)[1], $table))
				{
					$exist = true;
					break;
				}
			}
		}
		if (in_array($column, $this->query->getUsedColumns()))
		{
			$exist = true;
		}
		if (!$exist)
		{
			Logger::ORMLogging("No such column ($column) in used tables OR columns!", '[AGGREGATE]');
			throw new \Exception('ORM-exception',-2);
		}
		$function = match ($function)
		{
			self::AVERAGE => 'AVG',
			self::SUM => 'SUM',
			self::MIN => 'MIN',
			self::MAX => 'MAX',
			default => 'COUNT',
		};
		if($column === '*')
		{
			if (isset($as))
			{
				$query = str_replace('*',"$function(*) AS $as", $this->getQuery());
			}
			else
			{
				$query = str_replace('*',"$function(*)", $this->getQuery());
			}
			if (isset($groupBy))
			{
				$query = $query . " GROUP BY $groupBy";
			}
		}
		else
		{
			$query = explode(' FROM ', $this->getQuery())[0];
			$pattern = '/[A-Za-z]+\.[A-Za-z]+/';
			$matches = [];
			preg_match_all($pattern, $query, $matches);
			if (!in_array($column, $matches[0]))
			{
				Logger::ORMLogging("No such column ($column) is used in query", '[AGGREGATE]');
				throw new \Exception('ORM-exception',-2);
			}
			else
			{
				$query = $this->getQuery();
				$query = explode("$column", $query);
				if (isset($as))
				{
					$query[0] = $query[0] . "$function($column) AS $as";
					$this->query->addRenameToList("$column", "$as");
				}
				else
				{
					$query[0] = $query[0] . "$function($column)";
				}
				if (isset($groupBy))
				{
					$query = implode('', $query) . " GROUP BY $groupBy";
				}
				else
				{
					$query = implode('', $query) . " GROUP BY $column";
				}
			}
		}
		$this->query->setQuery($query);
		$this->query->addUsedFunction($function);
		return $this;
	}
	public function __toString(): string
	{
		return $this->query->getQuery();
	}
}