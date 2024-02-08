<?php

namespace Core\Database\Repo;

use App\Model\Category;
use App\Model\CategoryList;
use App\Service\DBHandler;
use App\Service\GetDbconnection;


class CategoryListRepo extends BaseRepo
{
	public static function getCategoryList(): \App\Model\CategoryList
	{
		$DBOperator = new DBHandler();
		$result = $DBOperator->query('SELECT * FROM category');
		$category_list=new CategoryList();
		while ($row = mysqli_fetch_assoc($result))
		{
			$ID=(int)$row['id'];
			$name=$row['name'];
			$category=new Category($ID, $name);
			$category_list->addCategory($category);
		}
		return $category_list;
	}
	public static function getObjectList(): \App\Model\CategoryList
	{
		$config = new \App\Config\Config();
		$dbNAME = $config->option('DB_NAME');
		$DBOperator = new DBHandler();
		$result = $DBOperator->query('SHOW TABLES');
		$object_list=new CategoryList();
		$tablePostfix = 'Tables_in_' . $dbNAME;
		$categoryBlackList=['image','items_category','migration','role','status'];
		$ID = 1;
		while ($row = mysqli_fetch_assoc($result))
		{
			if(!in_array($row[$tablePostfix], $categoryBlackList))
			{
				$category=new Category($ID, $row[$tablePostfix]);
				$object_list->addCategory($category);
				$ID++;
			}

		}
		return $object_list;
	}
}



