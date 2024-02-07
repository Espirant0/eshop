<?php

namespace Core\Database\Repo;

use App\Model\Category;
use App\Model\CategoryList;
use App\Service\GetDbconnection;


class CategoryListRepo extends BaseRepo
{
	public static function getCategoryList(): \App\Model\CategoryList
	{
		$Dbconnection = new GetDbconnection();
		$connection = $Dbconnection->createConnection();
		$mSQL = ' 
			SELECT *
			FROM category';

		$result = mysqli_query($connection, $mSQL);
		if (!$result)
		{
			throw new \Exception(mysqli_error($connection));
		}
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



