<?php

namespace App\Controller;

use App\Cache\FileCache;
use Core\Database\Repo\BicycleRepo;
use Core\Database\Repo\CategoryListRepo;

class IndexController extends BaseController
{
    public function showIndexPage($categoryName): void
    {
		$pageNumber = 1;
		if(isset($_GET['page']))
		{
			$pageNumber = $_GET['page'];
		}

		FileCache::deleteCacheByKey('bicycle');
		if (!isset($_GET['find']))
		{
			$property = '';
		}
		else $property = $_GET['find'];
        if (empty($categoryName))
        {
            $categoryName[] = '';
        }
		$bicycleList = BicycleRepo::getBicyclelist($pageNumber, $categoryName[0], $property);
		if($bicycleList == [])
		{
			$this->render('layout.php',[
				'content' => $this->strRender('MainPage/nullSearch.php', [
					'search' => $property,
				]),
				'categoryList' => CategoryListRepo::getCategoryListConsideringExistingItem(),
			]);
		}
        else
		{
			$this->render('layout.php', [
				'content' => $this->strRender('MainPage/index.php', [
					'categoryName' => $categoryName[0],
					'bicycleList' => $bicycleList,
				]),
				'categoryList' => CategoryListRepo::getCategoryListConsideringExistingItem(),
			]);
		}
    }
}