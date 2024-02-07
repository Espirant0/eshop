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
}



