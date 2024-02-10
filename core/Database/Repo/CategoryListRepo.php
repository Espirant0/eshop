<?php

namespace Core\Database\Repo;

use App\Config\Config;
use App\Model\Category;
use App\Model\CategoryList;
use App\Service\DBHandler;

class CategoryListRepo extends BaseRepo
{
	public static function getCategoryList(): CategoryList
	{
		$DBOperator = new DBHandler();
		$result = $DBOperator->query('SELECT id, name, engname FROM category');

        return self::createCategoryList($result);
	}

    public static function getCategoryListConsideringExistingItem(): CategoryList
    {
        $DBOperator = new DBHandler();
        $result = $DBOperator->query('SELECT DISTINCT c.id, c.name, c.engName
                                            FROM category c
                                            LEFT JOIN items_category ic ON c.id = ic.category_id;');

        return self::createCategoryList($result);
    }

    public static function createCategoryList($result): CategoryList
    {
        $categoryList = new CategoryList();

        while ($row = mysqli_fetch_assoc($result))
        {
            $categoryId = (int)$row['id'];
            $categoryName = $row['name'];
            $categoryEngName = $row['engname'];
            $category = new Category($categoryId, $categoryName, $categoryEngName);
            $categoryList->addCategory($category);
        }

        return $categoryList;
    }

	public static function getObjectList(): CategoryList
	{
		$config = new Config();
		$dbNAME = $config->option('DB_NAME');

		$DBOperator = new DBHandler();
		$result = $DBOperator->query('SHOW TABLES');

		$objectList = new CategoryList();
		$tablePostfix = 'Tables_in_' . $dbNAME;
		$categoryBlackList = $config->option('CATEGORY_BLACK_LIST');

		$idIterator = 1;

		while ($row = mysqli_fetch_assoc($result))
		{
			if(!in_array($row[$tablePostfix], $categoryBlackList))
			{
				$category = new Category($idIterator, $config->option('DICTIONARY')[$row[$tablePostfix]], '');
				$objectList->addCategory($category);
				$idIterator++;
			}
		}

		return $objectList;
	}
}
