<?php

namespace App\Controller;

use App\Cache\FileCache;
use App\Config\Config;
use Core\Database\Repo\BicycleRepo;
use Core\Database\Repo\CategoryListRepo;

class IndexController extends BaseController
{
    public function showIndexPage($categoryName): void
    {
		$config = new Config();
		$pageLimit = (int)$config->option('PRODUCT_LIMIT');
		$pagesCount = $this->getPagesCount($pageLimit, 'item');
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
					'category_name' => $categoryName[0],
					'bicycleList' => $bicycleList,
					'pagesCount' => $pagesCount
				]),
				'categoryList' => CategoryListRepo::getCategoryListConsideringExistingItem(),
			]);
		}
    }
}