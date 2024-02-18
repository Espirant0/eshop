<?php

namespace App\Controller;
use App\Cache\FileCache;
use Core\Database\Repo\AdminPanelRepo;
use Core\Database\Repo\BicycleRepo;
use Core\Database\Repo\CategoryListRepo;

class IndexController extends BaseController
{
    public function showIndexPage($categoryName): void
    {
		FileCache::deleteCacheByKey('bicycle');

		if (!isset($_GET['find']))
		{
			$property = '';
		}
		else
        {
            $property = $_GET['find'];
        }

        if (empty($categoryName))
        {
            $categoryName[] = '';
        }

		$bicycleList = BicycleRepo::getBicycleListConsideringCategoryName($categoryName[0], $property);

		if ($bicycleList == [])
		{
			echo $this->render('layout.php',[
				'content' => $this->render('MainPage/nullSearch.php', [
					'search' => $property,
				]),
				'categoryList' => CategoryListRepo::getCategoryListConsideringExistingItem(),
			]);
		}
        else
		{
			echo $this->render('layout.php', [
				'content' => $this->render('MainPage/index.php', [
					'category_name' => $categoryName[0],
					'bicycleList' => $bicycleList,
				]),
				'categoryList' => CategoryListRepo::getCategoryListConsideringExistingItem(),
			]);
		}
    }
}