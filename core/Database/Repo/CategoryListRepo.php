<?php

namespace Core\Database\Repo;

use App\Cache\FileCache;
use App\Config\Config;
use App\Model\Category;
use App\Model\CategoryList;
use App\Service\DBHandler;

class CategoryListRepo extends BaseRepo
{
	public static function getCategoryListConsideringExistingItem(): CategoryList
	{
		return (new FileCache())->remember('categoriesWithoutEmptyCategory', 3600, function () {
			$DBOperator = DBHandler::getInstance();
			$result = $DBOperator->query('SELECT DISTINCT c.id, c.name, c.engName
                                            FROM category c
                                            LEFT JOIN items_category ic ON c.id = ic.category_id
                                            JOIN item i ON i.id = ic.item_id
												WHERE i.status = 1;');

			return self::createCategoryList($result);
		});
	}

	public static function createCategoryList($result): CategoryList
	{
		$categoryList = new CategoryList();

		while ($row = mysqli_fetch_assoc($result))
		{
			$categoryId = (int)$row['id'];
			$categoryName = $row['name'];
			$categoryEngName = $row['engName'];
			$category = new Category($categoryId, $categoryName, $categoryEngName);
			$categoryList->addCategory($category);
		}

		return $categoryList;
	}

	public function getObjectList(): CategoryList
	{
		$config = new Config();
		$dbName = $config->option('DB_NAME');

		$DBOperator = DBHandler::getInstance();
		$result = $DBOperator->query('SHOW TABLES');

		$objectList = new CategoryList();
		$tablePostfix = 'Tables_in_' . strtolower($dbName);
		$categoryBlackList = $config->option('CATEGORY_BLACK_LIST');

		$idIterator = 0;
		while ($row = mysqli_fetch_assoc($result))
		{
			if (!in_array($row[$tablePostfix], $categoryBlackList))
			{
				$category = new Category($idIterator, $config->option('DICTIONARY')[$row[$tablePostfix]], $row[$tablePostfix]);
				$objectList->addCategory($category);
				$idIterator++;
			}
		}
		return $objectList;
	}
}