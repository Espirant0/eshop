<?php

namespace Core\Database\Repo;

use App\Model\Category;
use App\Model\CategoryList;
use App\Service\DBHandler;

class CategoryListRepo extends BaseRepo
{
	public static function getCategoryList(): \App\Model\CategoryList
	{
		$DBOperator = new DBHandler();
		$result = $DBOperator->query('SELECT id, name, engname FROM category');

        return self::createCategoryList($result);
	}

    public static function getCategoryListConsideringExistingItem(): \App\Model\CategoryList
    {
        $DBOperator = new DBHandler();
        $result = $DBOperator->query('SELECT DISTINCT c.id, c.name, c.engname
                                            FROM category c
                                            LEFT JOIN items_category ic ON c.id = ic.category_id;');

        return self::createCategoryList($result);
    }

    public static function createCategoryList($result): \App\Model\CategoryList
    {
        $categoryList = new CategoryList();

        while ($row = mysqli_fetch_assoc($result))
        {
            $ID = (int)$row['id'];
            $name = $row['name'];
            $engName = $row['engname'];
            $category = new Category($ID, $name, $engName);
            $categoryList->addCategory($category);
        }

        return $categoryList;
    }

	public static function getObjectList(): \App\Model\CategoryList
	{
		$config = new \App\Config\Config();
		$dbNAME = $config->option('DB_NAME');

		$DBOperator = new DBHandler();
		$result = $DBOperator->query('SHOW TABLES');

		$objectList = new CategoryList();
		$tablePostfix = 'Tables_in_' . $dbNAME;
		$categoryBlackList = $config->option('CATEGORY_BLACK_LIST');

		$ID = 1;

		while ($row = mysqli_fetch_assoc($result))
		{
			if(!in_array($row[$tablePostfix], $categoryBlackList))
			{
				$category = new Category($ID, $config->option('DICTIONARY')[$row[$tablePostfix]], '');
				$objectList->addCategory($category);
				$ID++;
			}
		}

		return $objectList;
	}
}
