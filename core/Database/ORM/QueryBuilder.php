<?php

namespace Core\Database\ORM;

use App\Config\Config;
use App\Service\DBHandler;
use App\Service\Logger;

class QueryBuilder
{
	private Query $query;

	public function __construct(Query $query = new Query('', ''))
	{
		$this->query = $query;
	}

	public function getQueryObject(): Query
	{
		return $this->query;
	}

	public function getQuery(): string
	{
		return $this->query->getQuery();
	}

	public static function getTableRestrictions(string $table): array
	{
		$config = new Config();
		$dbName = $config->option("DB_NAME");
		$result = DBHandler::getInstance()->getResult("SELECT COLUMN_NAME, DATA_TYPE, COLUMN_DEFAULT, CHARACTER_MAXIMUM_LENGTH, COLUMN_KEY, EXTRA  FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '$dbName' AND TABLE_NAME = '$table'");
		$restrictions = [];
		$pos = 1;
		foreach ($result as $res)
		{
			if ($res['COLUMN_DEFAULT'] == '')
			{
				if ($res['CHARACTER_MAXIMUM_LENGTH'] == '') $restrictions[$res['COLUMN_NAME']] = $res['DATA_TYPE'];
				else $restrictions[$res['COLUMN_NAME']] = $res['DATA_TYPE'] . ':' . $res['CHARACTER_MAXIMUM_LENGTH'];
			} else
			{
				if ($res['CHARACTER_MAXIMUM_LENGTH'] == '') $restrictions[$res['COLUMN_NAME']] = $res['DATA_TYPE'] . ', default=' . $res['COLUMN_DEFAULT'];
				else $restrictions[$res['COLUMN_NAME']] = $res['DATA_TYPE'] . ':' . $res['CHARACTER_MAXIMUM_LENGTH'] . ', default=' . $res['COLUMN_DEFAULT'];
			}
			if ($res["COLUMN_KEY"] != '') $restrictions[$res['COLUMN_NAME']] = $restrictions[$res['COLUMN_NAME']] . ", REQUIRED({$res['COLUMN_KEY']})";
			if ($res["EXTRA"] != '') $restrictions[$res['COLUMN_NAME']] = $restrictions[$res['COLUMN_NAME']] . ", {$res['EXTRA']}";
			$restrictions[$res['COLUMN_NAME']] = $restrictions[$res['COLUMN_NAME']] . ", pos=$pos";
			$pos++;
		}
		return $restrictions;
	}

	public static function isTableExists(string $table): bool
	{
		$config = new Config();
		$dbName = $config->option("DB_NAME");
		$dbTables = DBHandler::getInstance()->getResult("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$dbName' AND TABLE_TYPE = 'BASE TABLE'");
		foreach ($dbTables as $dbTable)
		{
			if ($dbTable['TABLE_NAME'] == $table) return true;
		}
		return false;
	}

	public static function getTableColumnsNames(string $table): array
	{
		$columnNames = [];
		$columns = DBHandler::getInstance()->getResult("SHOW COLUMNS FROM $table");
		foreach ($columns as $column)
		{
			$columnNames[] = $column['Field'];
		}
		return $columnNames;
	}

	public static function isColumnExistInTable(string $columnName, string $table): bool
	{
		$columnNames = self::getTableColumnsNames($table);
		if (in_array($columnName, $columnNames))
		{
			return true;
		}
		return false;
	}

	private static function itemListHandler(string $items, string $table): string
	{
		$items = explode(', ', $items);
		$queryItems = '';
		foreach ($items as $item)
		{
			if (self::isColumnExistInTable($item, $table))
			{
				if ($queryItems == '')
				{
					$queryItems = $table . '.' . $item;
				} else $queryItems = $queryItems . ', ' . $table . '.' . $item;
			} else Logger::ORMLogging("column $item is not exists in table $table this column will be skipped");
		}
		return $queryItems;
	}

	public static function select(string $itemList, string $table, bool $blacklist = false): self
	{
		if (strtolower($itemList) === 'all')
		{
			$itemList = implode(', ', self::getTableColumnsNames($table));
			$blacklist = false;
		}
		foreach (explode(', ', $itemList) as $item)
		{
			if (!self::isColumnExistInTable($item, $table))
			{
				Logger::ORMLogging("column $item is not exists in $table. Orm is working in 'SELECT * from $table' mode", 'SELECT-function');
				return new self(new Query("SELECT * FROM $table", $table));
			}
		}
		if (strtolower($itemList) != 'all') $items = self::itemListHandler($itemList, $table);
		if ($blacklist)
		{
			$columns = self::getTableColumnsNames($table);
			$items = self::itemListHandler(implode(', ', array_diff($columns, explode(', ', $itemList))), $table);
		}
		$query = new Query("SELECT $items FROM $table", $table);
		$query->addUsedFunction('SELECT');
		$query->addUsedColumns($items);
		return new self($query);
	}

	public function join(string $itemList, string $table, string $by = 'id', int $flag = INNER): self
	{
		$items = ', ' . self::itemListHandler($itemList, $table);
		$joinType = match ($flag)
		{
			LEFT => 'LEFT JOIN',
			RIGHT => 'RIGHT JOIN',
			FULL => 'FULL JOIN',
			CROSS => 'CROSS JOIN',
			default => 'INNER JOIN',
		};
		$usedTables = $this->query->getQueryTables();
		if ($by == 'id')
		{
			foreach ($usedTables as $usedTable)
			{
				$tableColumns = self::getTableColumnsNames($usedTable);
				if (in_array("$table" . "_id", $tableColumns))
				{
					$this->query->addToQuery("$joinType $table ON $table.id = $usedTable.$table" . "_id");
					$this->query->addQueryTable($table);
					$this->query->addUsedFunction($joinType);
					break;
				}
			}
		} else
		{
			$query = "$joinType $table ON ";
			$by = explode('=', str_replace(' ', '', $by));
			$tableColumns = self::getTableColumnsNames($table);
			if (in_array(explode('.', $by[0])[1], $tableColumns))
			{
				$query = $query . "$by[0] = ";
			}
			foreach ($usedTables as $usedTable)
			{
				$tableColumns = self::getTableColumnsNames($usedTable);
				if (in_array(explode('.', $by[1])[1], $tableColumns))
				{
					$query = $query . $by[1];
					break;
				}
			}
			$this->query->addToQuery($query);
			$this->query->addQueryTable($table);
			$this->query->addUsedFunction($joinType);
		}
		$query = $this->query->getQuery();
		$query = explode(' FROM ', $query);
		$query[0] = $query[0] . "$items FROM ";
		$this->query->setQuery(implode('', $query));
		return $this;
	}

	public function where(string|QueryBuilder $condition, ?QueryBuilder $selectQuery = null, string $typeOfAddition = 'AND'): self
	{
		if ($selectQuery instanceof QueryBuilder)
		{
			foreach ($selectQuery->getQueryObject()->getUsedFunctions() as $function) $this->query->addUsedFunction($function);
			$selectQuery = $selectQuery->getQueryObject();
			$this->query->addToQuery(" WHERE $condition IN($selectQuery)");
			$this->query->addUsedFunction('WHERE');
			return $this;
		}
		if (!in_array('WHERE', $this->query->getUsedFunctions()))
		{
			$this->query->addToQuery('WHERE ' . $condition);
		} else
		{
			if (strtolower($typeOfAddition) != ('and' || 'or' || 'not'))
			{
				Logger::ORMLogging("unknown condition statement ($typeOfAddition) will use AND instead");
				$typeOfAddition = 'AND';
			}
			$this->query->addToQuery("$typeOfAddition WHERE " . $condition);
		}
		$this->query->addUsedFunction('WHERE');
		return $this;
	}

	public function orderBy(string $condition, int $flag = ASCENDING): self
	{
		$order = match ($flag)
		{
			DESCENDING => 'DESC',
			default => 'ASC',
		};
		if (in_array($condition, $this->query->getUsedColumns()))
		{
			$this->query->addToQuery(" ORDER BY $condition $order");
			$this->query->addUsedFunction('ORDER');
		} else Logger::ORMLogging("column $condition that is used to order query is not exists in query. This operation will be skipped");
		return $this;
	}

	public function refactorTableName(string $nameToChange, string $asName): self
	{
		if (in_array($nameToChange, $this->query->getQueryTables()))
		{
			$query = str_replace(" $nameToChange ", " $nameToChange $asName ", $this->getQuery());
			$query = str_replace("$nameToChange.", "$asName.", $query);
			$this->query->setQuery($query);
			$tables = $this->query->getQueryTables();
			$newTables = [];
			foreach ($tables as $table)
			{
				if ($table === $nameToChange) $newTables[] = $nameToChange;
				else $newTables[] = $table;
			}
			$this->query->setQueryTables($newTables);
		}
		return $this;
	}

	public function as(string|array $nameToApply, string|array $asName): self
	{
		if (is_array($nameToApply) && is_array($asName) && count($nameToApply) == count($asName))
		{
			$maxPos = count($nameToApply);
			for ($i = 1; $i <= $maxPos; $i++)
			{
				$query = explode('FROM', $this->getQuery());
				$query[0] = str_replace("$nameToApply[$i],", "$nameToApply[$i] AS $asName[$i],", $query[0]);
				$this->query->setQuery(implode('FROM', $query));
				$this->query->addRenameToList($nameToApply[$i], $asName[$i]);
			}
		} elseif (is_string($nameToApply) && is_string($asName))
		{
			$query = explode('FROM', $this->getQuery());
			$query[0] = str_replace("$nameToApply,", "$nameToApply AS $asName,", $query[0]);
			$this->query->setQuery(implode('FROM', $query));
			$this->query->addRenameToList($nameToApply, $asName);
		} else
		{
			Logger::ORMLogging("Different count of arrays. Will use first of all of arrays.", 'as-operation');
			$nameToApply = $nameToApply[0];
			$asName = $asName[0];
		}
		return $this;
	}

	public static function insert(string $table, array|string $column, array|string $value, array $validationRules = []): bool
	{
		$columnRestrictions = self::getTableRestrictions($table);
		if ($column = '*')
		{
			$column = '';
			foreach ($columnRestrictions as $res)
			{
				if (!str_contains($res, 'auto_increment')) $column = $column . array_search($res, $columnRestrictions) . ', ';
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
		$config = new Config();
		$restrictions = $config->option("DB_CHARACTERS");
		if (!self::isTableExists($table)) return false;
		$requiredColumns = [];
		foreach ($columnRestrictions as $restriction)
		{
			if (str_contains($restriction, 'REQUIRED') && !str_contains($restriction, 'auto_increment')) $requiredColumns[] = array_search($restriction, $columnRestrictions);
		}
		if (is_array($column) && is_array($value))
		{
			if (array_diff($requiredColumns, $column) != [])
			{
				Logger::ORMLogging("INCORRECT amount of values. Want at least " . count($requiredColumns) . ' and get ' . count($column), 'ORM-INSERT');
				return false;
			}
			$columnDefaultCount = 0;
			foreach ($column as $col) if (str_contains('default', $columnRestrictions[$col]) || str_contains('auto_increment', $columnRestrictions[$col])) $columnDefaultCount++;
			if (count($value) >= count($column) - $columnDefaultCount)
			{
				$columns = implode(', ', $column);
				$query = "INSERT INTO $table" . "($columns) VALUES(";
				$valueKey = 0;
				foreach ($column as $col)
				{
					if (!self::isColumnExistInTable($col, $table)) return false;
					$value[$valueKey] = mysqli_real_escape_string(DBHandler::getInstance(), $value[$valueKey]);
					$splittedString = explode(', ', $columnRestrictions[$col])[0];
					$maxChar = explode(':', $splittedString);
					if (count($maxChar) > 1) $maxChar = $maxChar[1];
					else unset($maxChar);
					if (str_contains('auto_increment', $columnRestrictions[$col])) return false;
					if (array_search(explode(':', $splittedString)[0], $restrictions) === 'int')
					{
						if (is_numeric($value[$valueKey]))
						{
							if (isset($maxChar))
							{
								if ($maxChar > mb_strlen($value[$valueKey]))
								{
									$value[$valueKey] = (int)$value[$valueKey];
									$query = $query . "$value[$valueKey], ";
									$valueKey++;
								} else return false;
							} else
							{
								$value[$valueKey] = (int)$value[$valueKey];
								$query = $query . "$value[$valueKey], ";
								$valueKey++;
							}
						} else return false;
					} else
					{
						if (isset($maxChar))
						{
							if ($maxChar > mb_strlen($value[$valueKey]))
							{
								$value[$valueKey] = (string)$value[$valueKey];
								$query = $query . "'$value[$valueKey]', ";
								$valueKey++;
							} else return false;
						} else
						{
							$value[$valueKey] = (string)$value[$valueKey];
							$query = $query . "'$value[$valueKey]', ";
							$valueKey++;
						}
					}
				}
				$query = $query . ")";
				$query = str_replace(', )', ')', $query);
			}
		}
		if (!isset($query)) return false;
		DBHandler::getInstance()->query($query);
		return true;
	}

	public static function update(string $table, array|string $column, array|string $newValue, array|string|int $updateConditions, array $validationRules = [])
	{
		$config = new Config();
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
		if (is_string($updateConditions) && !is_int($updateConditions))
		{
			$updateConditions = str_replace(', ', ',', $updateConditions);
			$updateConditions = explode(',', $updateConditions);
		}
		if (is_int($updateConditions))
		{
			$updateConditions = ["id = $updateConditions"];
		}
		$columnRestrictions = self::getTableRestrictions($table);
		if (self::isTableExists($table))
		{
			$valueKey = 0;
			if (count($column) != count($newValue)) return false;
			if (count($column) != count($updateConditions) && count($updateConditions) != 1) return false;
			if (count($updateConditions) > 1) $conditionsKey = 0;
			$queryList = [];
			foreach ($column as $col)
			{
				$query = "UPDATE $table SET ";
				$newValue[$valueKey] = mysqli_real_escape_string(DBHandler::getInstance(), $newValue[$valueKey]);
				$splittedString = explode(', ', $columnRestrictions[$col])[0];
				$maxChar = explode(':', $splittedString);
				if (count($maxChar) > 1) $maxChar = $maxChar[1];
				else unset($maxChar);
				if (self::isColumnExistInTable($col, $table))
				{
					if (str_contains('auto_increment', $columnRestrictions[$col])) return false;
					$query = $query . "$col = ";
					if (array_search(explode(':', $splittedString)[0], $restrictions) === 'int')
					{
						if (is_numeric($newValue[$valueKey]))
						{
							if (isset($maxChar))
							{
								if ($maxChar > mb_strlen($newValue[$valueKey]))
								{
									$newValue[$valueKey] = (int)$newValue[$valueKey];
									if (isset($conditionsKey))
									{
										$query = $query . "$newValue[$valueKey] WHERE $updateConditions[$conditionsKey]";
										$conditionsKey++;
									} else $query = $query . "$newValue[$valueKey] WHERE $updateConditions[0]";
									$valueKey++;
								} else return false;
							} else
							{
								if (isset($conditionsKey))
								{
									$query = $query . "$newValue[$valueKey] WHERE $updateConditions[$conditionsKey]";
									$conditionsKey++;
								} else $query = $query . "$newValue[$valueKey] WHERE $updateConditions[0]";
								$valueKey++;
							}
						} else return false;
					} else
					{
						if (isset($maxChar))
						{
							if ($maxChar > mb_strlen($newValue[$valueKey]))
							{
								$newValue[$valueKey] = (string)$newValue[$valueKey];
								if (isset($conditionsKey))
								{
									$query = $query . "'$newValue[$valueKey]' WHERE $updateConditions[$conditionsKey]";
									$conditionsKey++;
								} else $query = $query . "'$newValue[$valueKey]' WHERE $updateConditions[0]";
								$valueKey++;
							} else return false;
						} else
						{
							$newValue[$valueKey] = (string)$newValue[$valueKey];
							if (isset($conditionsKey))
							{
								$query = $query . "'$newValue[$valueKey]' WHERE $updateConditions[$conditionsKey]";
								$conditionsKey++;
							} else $query = $query . "'$newValue[$valueKey]' WHERE $updateConditions[0]";
							$valueKey++;
						}
					}
				} else return false;
				$queryList[] = $query;
			}
			var_dump($queryList);
			foreach ($queryList as $query)
			{
				if (!(new Query($query, $table))->testQuery()) return false;
			}
			foreach ($queryList as $query)
			{
				DBHandler::getInstance()->query($query);
			}
		}
		return true;
	}

	public function aggregate(string $column, int $function = COUNT, ?string $as = null, ?string $groupBy = null): self
	{
		$agregateTable = $this->query->getQueryTables();
		$exist = false;
		foreach ($agregateTable as $table)
		{
			if (self::isColumnExistInTable(explode('.', $column)[1], $table))
			{
				$exist = true;
				break;
			}
		}
		if (!$exist) return $this;
		$function = match ($function)
		{
			AVERAGE => 'AVG',
			SUM => 'SUM',
			MIN => 'MIN',
			MAX => 'MAX',
			default => 'COUNT',
		};
		$query = explode(' FROM ', $this->getQuery())[0];
		$pattern = '/[A-Za-z]+\.[A-Za-z]+/';
		$matches = [];
		preg_match_all($pattern, $query, $matches);
		var_dump($matches);
		if (!in_array($column, $matches[0])) return $this;
		else
		{
			$query = $this->getQuery();
			$query = explode("$column", $query);
			if (isset($as))
			{
				$query[0] = $query[0] . "$function($column) AS $as";
				$this->query->addRenameToList("$column", "$as");
			} else $query[0] = $query[0] . "$function($column)";
			if (isset($groupBy)) $query = implode('', $query) . " GROUP BY $groupBy";
			else $query = implode('', $query) . " GROUP BY $column";
			$this->query->setQuery($query);
			$this->query->addUsedFunction($function);
		}
		return $this;
	}
}