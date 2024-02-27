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
		$itemsPerPage = $config->option('PRODUCT_LIMIT');
		$pageNumber = 1;

		if (isset($_GET['page']))
		{
			$pageNumber = (int)$_GET['page'];
			unset($_GET['page']);
		}

		$property = [];
		FileCache::deleteCacheByKey('bicycle');

		if (count($_GET) > 0)
		{
			$property = $_GET;
		}

		$httpQuery = $_GET;

		if (empty($categoryName))
		{
			if (isset($_GET['category']))
			{
				$categoryName[] = $_GET['category'];
			}
			else
			{
				$categoryName[] = '';
			}
		}
		else
		{
			$httpQuery['category'] = $categoryName[0];
		}

		if (!isset($property['search']))
		{
			$search = null;
		}
		else
		{
			$search = htmlspecialchars($property['search']);
		}

		$bicycleList = BicycleRepo::getBicycleList($pageNumber, $categoryName[0], $property);

		if ($bicycleList == [] || $pageNumber < 1)
		{
			echo $this->render('layout.php', ['content' => $this->render('MainPage/nullSearch.php',
				['search' => $search,]), 'categoryList' => CategoryListRepo::getCategoryListConsideringExistingItem(), 'title' => 'Ничего не найдено']);
		}
		else
		{
			echo $this->render('layout.php', ['content' => $this->render('MainPage/index.php',
				['categoryName' => $categoryName[0], 'bicycleList' => $bicycleList, 'page' => $pageNumber, 'httpQuery' => http_build_query($httpQuery), 'pagesCount' => $this->getPagesCount($itemsPerPage,
					'item'),]), 'categoryList' => CategoryListRepo::getCategoryListConsideringExistingItem(), 'title' => TITLE, 'categoryName' => $categoryName[0],]);
		}
	}
}